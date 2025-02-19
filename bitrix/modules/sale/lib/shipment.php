<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage sale
 * @copyright 2001-2012 Bitrix
 */
namespace Bitrix\Sale;

use Bitrix\Catalog\VatTable;
use Bitrix\Main;
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Delivery;
use Bitrix\Sale\Internals;
use Bitrix\Sale\Services\Company;
use \Bitrix\Sale\Delivery\Requests;

Loc::loadMessages(__FILE__);

class Shipment
	extends Internals\CollectableEntity
	implements IBusinessValueProvider, \IEntityMarker
{
	/** @var array ShipmentItemCollection */
	protected $shipmentItemCollection;

	/** @var  Delivery\Services\Base */
	protected $deliveryService = null;

	protected $extraServices = null;

	protected $storeId = 0;

	/** @var int */
	protected $internalId = 0;

	protected static $idShipment = 0;

	protected static $mapFields = array();

	private static $eventClassName = null;

	const ENTITY_MARKER_AUTOFIX_TYPE_ACTION_RESERVE = "RESERVE";
	const ENTITY_MARKER_AUTOFIX_TYPE_ACTION_SHIP = "SHIP";

	/**
	 * @return int
	 */
	public function getShipmentCode()
	{
		if ($this->internalId == 0)
		{
			static::$idShipment++;
			$this->internalId = static::$idShipment;
		}
		return $this->internalId;
	}


	/**
	 * @return array
	 */
	public static function getAvailableFields()
	{
		return array("STATUS_ID", "BASE_PRICE_DELIVERY", "PRICE_DELIVERY", "ALLOW_DELIVERY", "DATE_ALLOW_DELIVERY", "EMP_ALLOW_DELIVERY_ID", "DEDUCTED", "DATE_DEDUCTED", "EMP_DEDUCTED_ID", "REASON_UNDO_DEDUCTED", "DELIVERY_ID", "DELIVERY_DOC_NUM", "DELIVERY_DOC_DATE", "TRACKING_NUMBER", "XML_ID", "PARAMS", "DELIVERY_NAME", "COMPANY_ID", "MARKED", "DATE_MARKED", "EMP_MARKED_ID", "REASON_MARKED", "CANCELED", "DATE_CANCELED", "EMP_CANCELED_ID", "RESPONSIBLE_ID", "DATE_RESPONSIBLE_ID", "EMP_RESPONSIBLE_ID", "COMMENTS", "CURRENCY", "CUSTOM_PRICE_DELIVERY", "UPDATED_1C","EXTERNAL_DELIVERY","VERSION_1C","ID_1C", "TRACKING_STATUS", "TRACKING_LAST_CHECK", "TRACKING_DESCRIPTION", "ACCOUNT_NUMBER", 'DISCOUNT_PRICE');
			// ID, ORDER_ID, RESERVED, SYSTEM
	}

	/**
	 * @return array
	 */
	public static function getMeaningfulFields()
	{
		return array('BASE_PRICE_DELIVERY', 'DELIVERY_ID');
	}

	/**
	 * @return array
	 */
	public static function getAllFields()
	{
		if (empty(static::$mapFields))
		{
			static::$mapFields = parent::getAllFieldsByMap(Internals\ShipmentTable::getMap());
		}
		return static::$mapFields;
	}

	/**
	 * @param Delivery\Services\Base $deliveryService
	 * @throws Main\NotSupportedException
	 */
	public function setDeliveryService(Delivery\Services\Base $deliveryService)
	{
		$this->deliveryService = $deliveryService;
		$resultSetting = $this->setField("DELIVERY_ID", $deliveryService->getId());
		if ($resultSetting->isSuccess())
		{
			$this->setField("DELIVERY_NAME", $deliveryService->getName());
		}
	}

	/**
	 * Use ShipmentCollection::createShipment instead
	 *
	 * @param ShipmentCollection $collection
	 * @param Delivery\Services\Base $deliveryService
	 * @return static
	 */
	public static function create(ShipmentCollection $collection, Delivery\Services\Base $deliveryService = null)
	{
		$fields = array(
			'ALLOW_DELIVERY' => 'N',
			'DEDUCTED' => 'N',
			'CUSTOM_PRICE_DELIVERY' => 'N',
			'MARKED' => 'N',
			'CANCELED' => 'N',
			'RESERVED' => 'N'
		);

		$deliveryStatus = DeliveryStatus::getInitialStatus();

		if (!empty($deliveryStatus) && !is_array($deliveryStatus))
		{
			$fields['STATUS_ID'] = $deliveryStatus;
		}

		$shipment = static::createShipmentObject();
		$shipment->setFieldsNoDemand($fields);
		$shipment->setCollection($collection);

		if ($deliveryService !== null)
		{
			$shipment->setDeliveryService($deliveryService);
		}

		return $shipment;
	}

	/**
	 * @param array $fields
	 * @return Shipment
	 */
	protected static function createShipmentObject(array $fields = array())
	{
		$registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
		$shipmentClassName = $registry->getShipmentClassName();

		return new $shipmentClassName($fields);
	}

	/**
	 * @internal
	 *
	 * @return bool
	 * @throws Main\ObjectNotFoundException
	 */
	public function needReservation()
	{
		$condition = Configuration::getProductReservationCondition();

		if ($condition == Configuration::RESERVE_ON_CREATE)
			return true;

		if ($condition == Configuration::RESERVE_ON_PAY
			|| $condition == Configuration::RESERVE_ON_FULL_PAY)
		{
			/** @var ShipmentCollection $collection */
			if (!$collection = $this->getCollection())
			{
				throw new Main\ObjectNotFoundException('Entity "ShipmentCollection" not found');
			}

			/** @var Order $order */
			if (!$order = $collection->getOrder())
			{
				throw new Main\ObjectNotFoundException('Entity "Order" not found');
			}
			if ($condition == Configuration::RESERVE_ON_FULL_PAY)
				return $order->isPaid();

			/** @var PaymentCollection $paymentCollection */
			if (!$paymentCollection = $order->getPaymentCollection())
			{
				throw new Main\ObjectNotFoundException('Entity "PaymentCollection" not found');
			}

			return $paymentCollection->hasPaidPayment();
		}

		if ($this->isSystem())
			return false;

		return (($condition == Configuration::RESERVE_ON_ALLOW_DELIVERY) && $this->isAllowDelivery()
			|| ($condition == Configuration::RESERVE_ON_SHIP) && $this->isShipped());
	}

	/**
	 * @param ShipmentItem $sourceItem
	 * @param $quantity
	 *
	 * @return Result
	 * @throws Main\ArgumentException
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\NotSupportedException
	 * @throws Main\ObjectNotFoundException
	 */
	private function transferItem2SystemShipment(ShipmentItem $sourceItem, $quantity)
	{
		/** @var ShipmentItemCollection $sourceItemCollection */
		$sourceItemCollection = $sourceItem->getCollection();
		if ($this !== $sourceItemCollection->getShipment())
			throw new Main\ArgumentException("item");

		$quantity = floatval($quantity);

		/** @var ShipmentCollection $shipmentCollection */
		if (!$shipmentCollection = $this->getCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentCollection" not found');
		}

		/** @var Shipment $systemShipment */
		if (!$systemShipment = $shipmentCollection->getSystemShipment())
		{
			throw new Main\ObjectNotFoundException('Entity "Shipment" not found');
		}

		/** @var BasketItem $basketItem */
		if (!$basketItem = $sourceItem->getBasketItem())
		{
			throw new Main\ObjectNotFoundException('Entity "BasketItem" not found');
		}

		/** @var Basket $basket */
		if (!$basket = $basketItem->getCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "Basket" not found');
		}

		/** @var Order $order */
		if (!$order = $basket->getOrder())
		{
			throw new Main\ObjectNotFoundException('Entity "Order" not found');
		}

		$shipmentItemCode = $sourceItem->getBasketCode();

		if ($quantity === 0)
			return new Result();

		/** @var ShipmentItemCollection $systemShipmentItemCollection */
		if (!$systemShipmentItemCollection = $systemShipment->getShipmentItemCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "System ShipmentItemCollection" not found');
		}

		$systemShipmentItem = $systemShipmentItemCollection->getItemByBasketCode($shipmentItemCode);
		if (is_null($systemShipmentItem))
			$systemShipmentItem = $systemShipmentItemCollection->createItem($basketItem);

		$newSystemShipmentItemQuantity = $systemShipmentItem->getQuantity() + $quantity;
		if ($newSystemShipmentItemQuantity < 0)
		{
			$result = new Result();
			$result->addError(
				new ResultError(
					str_replace(
						array("#NAME#", "#QUANTITY#"),
						array($sourceItem->getBasketItem()->getField("NAME"), abs($quantity)),
						Loc::getMessage('SALE_SHIPMENT_QUANTITY_MISMATCH')
					),
					'SALE_SHIPMENT_QUANTITY_MISMATCH'
				)
			);
			return $result;
		}

		$systemShipmentItem->setFieldNoDemand('QUANTITY', $newSystemShipmentItemQuantity);

		$affectedQuantity = 0;

		if ($quantity > 0)  // transfer to system shipment
		{
			if ($sourceItem->getReservedQuantity() > 0)
			{
				$affectedQuantity = $quantity;
				$originalQuantity = $sourceItem->getQuantity() + $quantity;
				if ($sourceItem->getReservedQuantity() < $originalQuantity)
					$affectedQuantity -= $originalQuantity - $sourceItem->getReservedQuantity();
			}
		}
		elseif ($quantity < 0)  // transfer from system shipment
		{
			if ($systemShipmentItem->getReservedQuantity() > 0)
			{
				$affectedQuantity = $quantity;
				if ($systemShipmentItem->getReservedQuantity() < -$affectedQuantity)
					$affectedQuantity = -1 * $systemShipmentItem->getReservedQuantity();
			}
		}

		if ($affectedQuantity != 0)  // if there are reserved items among transfered
		{
			$result = $sourceItem->setField(
				"RESERVED_QUANTITY", $sourceItem->getField('RESERVED_QUANTITY') - $affectedQuantity
			);
//			if (!$result->isSuccess(true))
//				return $result;

			$systemShipmentItem->setFieldNoDemand(
				'RESERVED_QUANTITY',
				$systemShipmentItem->getField('RESERVED_QUANTITY') + $affectedQuantity
			);

			$systemShipment->setFieldNoDemand(
				'RESERVED',
				($systemShipmentItem->getField("RESERVED_QUANTITY") > 0) ? "Y" : "N"
			);

			$shipmentItemForPool = $sourceItem;
			$sourceShipmentItemForPool = $systemShipmentItem;

			if ($quantity > 0)
			{
				$shipmentItemForPool = $systemShipmentItem;
				$sourceShipmentItemForPool = $sourceItem;
			}

			$productId = $basketItem->getProductId();

			$foundItem = false;
			$poolItems = Internals\ItemsPool::get($order->getInternalId(), $productId);
			if (!empty($poolItems))
			{
				$oldItem = null;
				foreach ($poolItems as $poolIndex => $poolItem)
				{
					if ($poolItem->getInternalIndex() == $shipmentItemForPool->getInternalIndex())
					{
						$foundItem = true;
					}
					
					if ($sourceShipmentItemForPool && $poolItem->getInternalIndex() == $sourceShipmentItemForPool->getInternalIndex())
					{
						$reserveQuantity = $sourceShipmentItemForPool->getReservedQuantity();
						if ($reserveQuantity == 0)
						{
							Internals\ItemsPool::delete($order->getInternalId(), $productId, $poolIndex);
						}
					}
				}
			}

			if (!$foundItem)
			{
				Internals\ItemsPool::add($order->getInternalId(), $productId, $shipmentItemForPool);
			}
		}

		$tryReserveResult = null;

		if ($quantity > 0)
		{
			if ($systemShipment->needReservation())
			{
				/** @var Result $tryReserveResult */
				$tryReserveResult = Provider::tryReserveShipmentItem($systemShipmentItem);
			}
			else
			{
				/** @var Result $tryReserveResult */
				$tryReserveResult = Provider::tryUnreserveShipmentItem($systemShipmentItem);
			}
		}
		elseif ($quantity < 0)  // transfer from system shipment
		{
			if ($sourceItemCollection->getShipment()->needReservation())
			{
				/** @var Result $tryReserveResult */
				$tryReserveResult = Provider::tryReserveShipmentItem($sourceItem);
			}
		}

		$canReserve = false;

		if ($tryReserveResult === null)
			$canReserve = true;

		if ($tryReserveResult !== null && ($tryReserveResult->isSuccess() && ($tryReserveResultData = $tryReserveResult->getData())))
		{
			if (array_key_exists('CAN_RESERVE', $tryReserveResultData))
			{
				$canReserve = $tryReserveResultData['CAN_RESERVE'];
			}
		}

		if ($systemShipment->needReservation() && $canReserve)
		{
			$order = $this->getParentOrder();
			if ($order &&
				!Internals\ActionEntity::isTypeExists(
					$order->getInternalId(),
					Internals\ActionEntity::ACTION_ENTITY_SHIPMENT_COLLECTION_RESERVED_QUANTITY
				)
			)
			{
				Internals\ActionEntity::add(
					$order->getInternalId(),
					Internals\ActionEntity::ACTION_ENTITY_SHIPMENT_COLLECTION_RESERVED_QUANTITY,
					array(
						'METHOD' => 'Bitrix\Sale\ShipmentCollection::updateReservedFlag',
						'PARAMS' => array($systemShipment->getCollection())
					)
				);
			}
		}


		return new Result();
	}

	/**
	 * @param Shipment $shipment
	 *
	 * @return Result
	 * @throws Main\ObjectNotFoundException
	 */
	public static function updateReservedFlag(Shipment $shipment)
	{
		$shipmentReserved = true;

		/** @var ShipmentItemCollection $shipmentItemCollection */
		if (!$shipmentItemCollection = $shipment->getShipmentItemCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentItemCollection" not found');
		}

		$shipmentItemList = $shipmentItemCollection->getShippableItems();

		if ($shipmentItemList->count() == 0)
		{
			$shipmentReserved = false;
		}

		/** @var ShipmentItem $shipmentItem */
		foreach ($shipmentItemList as $shipmentItem)
		{
			if ($shipmentItem->getQuantity() - $shipmentItem->getReservedQuantity())
			{
				$shipmentReserved = false;
				break;
			}
		}

		$shipmentReservedValue = $shipmentReserved ? "Y" : "N";
		$currentValue = $shipment->getField('RESERVED');
		if ($shipment->getField('RESERVED') != $shipmentReservedValue)
		{
			$eventManager = Main\EventManager::getInstance();
			$eventsList = $eventManager->findEventHandlers('sale', EventActions::EVENT_ON_BEFORE_SHIPMENT_RESERVE);
			if (!empty($eventsList))
			{
				/** @var Main\Entity\Event $event */
				$event = new Main\Event('sale', EventActions::EVENT_ON_BEFORE_SHIPMENT_RESERVE, array(
					'ENTITY' => $shipment,
					'VALUE' => $shipmentReservedValue,
				));

				$event->send();

				if ($event->getResults())
				{
					$result = new Result();
					/** @var Main\EventResult $eventResult */
					foreach($event->getResults() as $eventResult)
					{
						if($eventResult->getType() == Main\EventResult::ERROR)
						{
							$errorMsg = new ResultError(Main\Localization\Loc::getMessage('SALE_EVENT_ON_BEFORE_SHIPMENT_RESERVE_ERROR'), 'SALE_EVENT_ON_BEFORE_SHIPMENT_RESERVE_ERROR');

							$eventResultData = $eventResult->getParameters();
							if ($eventResultData)
							{
								if (isset($eventResultData) && $eventResultData instanceof ResultError)
								{
									/** @var ResultError $errorMsg */
									$errorMsg = $eventResultData;
								}
							}

							$result->addError($errorMsg);

						}
					}

					if (!$result->isSuccess())
					{
						return $result;
					}
				}
			}

			$shipment->setFieldNoDemand('RESERVED', $shipmentReserved ? "Y" : "N");

			Internals\EventsPool::addEvent('s'.$shipment->getInternalIndex(), EventActions::EVENT_ON_SHIPMENT_RESERVED, array(
				'ENTITY' => $shipment,
				'VALUE' => $shipmentReservedValue,
				'OLD_VALUE' => $currentValue,
			));
		}

		return new Result();
	}

	/**
	 * @param $action
	 * @param ShipmentItem $shipmentItem
	 * @param null $name
	 * @param null $oldValue
	 * @param null $value
	 * @return Result
	 * @throws Main\NotSupportedException
	 * @throws Main\SystemException
	 */
	public function onShipmentItemCollectionModify($action, ShipmentItem $shipmentItem, $name = null, $oldValue = null, $value = null)
	{
		if ($action != EventActions::UPDATE)
			return new Result();

		if ($this->isSystem() && ($name != 'RESERVED_QUANTITY'))
			throw new Main\NotSupportedException();

		if ($name === "QUANTITY")
		{
			return $this->transferItem2SystemShipment($shipmentItem, $oldValue - $value);
		}
		elseif ($name === 'RESERVED_QUANTITY')
		{
			$order = $this->getParentOrder();
			if ($order &&
				!Internals\ActionEntity::isTypeExists(
					$order->getInternalId(),
					Internals\ActionEntity::ACTION_ENTITY_SHIPMENT_COLLECTION_RESERVED_QUANTITY
				)
			)
			{
				Internals\ActionEntity::add(
					$order->getInternalId(),
					Internals\ActionEntity::ACTION_ENTITY_SHIPMENT_COLLECTION_RESERVED_QUANTITY,
					array(
						'METHOD' => 'Bitrix\Sale\ShipmentCollection::updateReservedFlag',
						'PARAMS' => array($this->getCollection())
					)
				);
			}
		}

		return new Result();
	}

	/**
	 * @internal
	 * 
	 * Deletes shipment without demands.
	 * 
	 * @param $idOrder
	 * @return Result
	 * @throws Main\NotSupportedException
	 * @throws Main\ObjectNotFoundException
	 */
	public static function deleteNoDemand($idOrder)
	{
		$result = new Result();
		
		$shipmentDataList = Internals\ShipmentTable::getList(
			array(
				"filter" => array("=ORDER_ID" => $idOrder),
				"select" => array("ID")
			)	
		);

		while ($shipment = $shipmentDataList->fetch())
		{
			$r = Internals\ShipmentTable::deleteWithItems($shipment['ID']);
			if ($r -> isSuccess())
			{
				Internals\ShipmentExtraServiceTable::deleteByShipmentId($shipment['ID']);
			}
			else
			{
				$result->addErrors($r->getErrors());
			}
		}

		return $result;
	}


	/**
	 * Deletes shipment
	 *
	 * @return Result
	 * @throws Main\NotSupportedException
	 * @throws Main\ObjectNotFoundException
	 */
	public function delete()
	{
		if ($this->isShipped())
		{
			$result = new Result();
			$result->addError(new ResultError(Loc::getMessage('SALE_SHIPMENT_EXIST_SHIPPED'), 'SALE_SHIPMENT_EXIST_SHIPPED'));
			return $result;
		}

		if ($this->isAllowDelivery())
			$this->disallowDelivery();

		if (!$this->isSystem())
			$this->setField('BASE_PRICE_DELIVERY', 0);

		/** @var ShipmentItemCollection $shipmentItemCollection */
		if (!$shipmentItemCollection = $this->getShipmentItemCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentItemCollection" not found');
		}

		Requests\Manager::onBeforeShipmentDelete($this);
		$shipmentItemCollection->clearCollection();
		return parent::delete();
	}

	public function dump($i)
	{
		return str_repeat(' ', $i)."Shipment: Id=".$this->getId().", ALLOW_DELIVERY=".$this->getField('ALLOW_DELIVERY').", DEDUCTED=".$this->getField('DEDUCTED').", RESERVED=".$this->getField('RESERVED').", SYSTEM=".$this->getField('SYSTEM')."\n".($this->getShipmentItemCollection()->dump($i + 1));
	}

	/**
	 * Sets new value to specified field of shipment item
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return Result
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\NotSupportedException
	 * @throws \Exception
	 */
	public function setField($name, $value)
	{
		if ($this->isSystem())
		{
			throw new Main\NotSupportedException();
		}

		if ($name == "DELIVERY_ID")
		{
			if (strval($value) != '' && !Delivery\Services\Manager::isServiceExist($value))
			{
				$result = new Result();
				$result->addError( new ResultError(Loc::getMessage('SALE_SHIPMENT_WRONG_DELIVERY_SERVICE'), 'SALE_SHIPMENT_WRONG_DELIVERY_SERVICE') );
			}

		}

		$priceRoundedFields = array(
			'BASE_PRICE_DELIVERY' => 'BASE_PRICE_DELIVERY',
			'PRICE_DELIVERY' => 'PRICE_DELIVERY',
			'DISCOUNT_PRICE' => 'DISCOUNT_PRICE',
		);
		if (isset($priceRoundedFields[$name]))
		{
			$value = PriceMaths::roundPrecision($value);
		}

		return parent::setField($name, $value);
	}

	/**
	 * @internal
	 *
	 * @param $name
	 * @param $value
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public function setFieldNoDemand($name, $value)
	{
		$priceRoundedFields = array(
			'BASE_PRICE_DELIVERY' => 'BASE_PRICE_DELIVERY',
			'PRICE_DELIVERY' => 'PRICE_DELIVERY',
			'DISCOUNT_PRICE' => 'DISCOUNT_PRICE',
		);
		if (isset($priceRoundedFields[$name]))
		{
			$value = PriceMaths::roundPrecision($value);
		}

		parent::setFieldNoDemand($name, $value);
	}

	/**
	 * @param $id
	 * @return array
	 * @throws Main\ArgumentException
	 * @throws Main\ArgumentNullException
	 */
	public static function loadForOrder($id)
	{
		if (intval($id) <= 0)
			throw new Main\ArgumentNullException("id");

		$shipments = array();

		$shipmentDataList = Internals\ShipmentTable::getList(
			array(
				'filter' => array('ORDER_ID' => $id),
				'order' => array('SYSTEM' => 'ASC', 'DATE_INSERT' => 'ASC', 'ID' => 'ASC')
			)
		);
		while ($shipmentData = $shipmentDataList->fetch())
			$shipments[] = static::createShipmentObject($shipmentData);


		return $shipments;
	}

	/**
	 * @return Result
	 * @throws Main\ObjectNotFoundException
	 */
	public function save()
	{
		$result = new Result();
		$id = $this->getId();
		$fields = $this->fields->getValues();

		if (self::$eventClassName === null)
		{
			self::$eventClassName = static::getEntityEventName();
		}

		$isNew = ($this->getId() == 0);
		$oldEntityValues = $this->fields->getOriginalValues();


		/** @var ShipmentItemCollection $shipmentItemCollection */
		if (!$shipmentItemCollection = $this->getShipmentItemCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentItemCollection" not found');
		}

		/** @var Shipment $shipment */
		if (!$shipment = $shipmentItemCollection->getShipment())
		{
			throw new Main\ObjectNotFoundException('Entity "Shipment" not found');
		}

		/** @var ShipmentCollection $shipmentCollection */
		if (!$shipmentCollection = $shipment->getCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentCollection" not found');
		}

		/** @var Order $order */
		if (!$order = $shipmentCollection->getOrder())
		{
			throw new Main\ObjectNotFoundException('Entity "Order" not found');
		}

		if ($this->isChanged() && self::$eventClassName)
		{
			/** @var Main\Entity\Event $event */
			$event = new Main\Event('sale', 'OnBefore'.self::$eventClassName.'EntitySaved', array(
					'ENTITY' => $this,
					'VALUES' => $this->fields->getOriginalValues()
			));
			$event->send();
		}

		$isChanged = $this->isChanged();

		if ($id > 0)
		{
			if ($isChanged)
			{
				Requests\Manager::onBeforeShipmentSave($order, $this);
			}

			$fields = $this->fields->getChangedValues();

			if (!empty($fields) && is_array($fields))
			{
				if (array_key_exists('REASON_MARKED', $fields) && strlen($fields['REASON_MARKED']) > 255)
				{
					$fields['REASON_MARKED'] = substr($fields['REASON_MARKED'], 0, 255);

					$this->setFieldNoDemand('REASON_MARKED', $fields['REASON_MARKED']);
				}

				$r = Internals\ShipmentTable::update($id, $fields);
				if (!$r->isSuccess())
				{

					OrderHistory::addAction(
						'SHIPMENT',
						$order->getId(),
						'SHIPMENT_UPDATE_ERROR',
						$id,
						$this,
						array("ERROR" => $r->getErrorMessages())
					);

					$result->addErrors($r->getErrors());
					return $result;
				}

				if ($resultData = $r->getData())
					$result->setData($resultData);
			}

			if (!empty($fields['TRACKING_NUMBER']))
			{
				$oldEntityValues = $this->fields->getOriginalValues();

				/** @var Main\Event $event */
				$event = new Main\Event('sale', EventActions::EVENT_ON_SHIPMENT_TRACKING_NUMBER_CHANGE, array(
					'ENTITY' => $this,
					'VALUES' => $oldEntityValues,
				));
				$event->send();

				Notify::callNotify($shipment, EventActions::EVENT_ON_SHIPMENT_TRACKING_NUMBER_CHANGE);
			}

		}
		else
		{
			$fields['ORDER_ID'] = $this->getParentOrderId();
			$this->setFieldNoDemand('ORDER_ID', $fields['ORDER_ID']);

			$fields['DATE_INSERT'] = new Main\Type\DateTime();
			$this->setFieldNoDemand('DATE_INSERT', $fields['DATE_INSERT']);

			$fields['SYSTEM'] = $fields['SYSTEM']? 'Y' : 'N';
			$this->setFieldNoDemand('SYSTEM', $fields['SYSTEM']);

			if (array_key_exists('REASON_MARKED', $fields) && strlen($fields['REASON_MARKED']) > 255)
			{
				$fields['REASON_MARKED'] = substr($fields['REASON_MARKED'], 0, 255);

				$this->setFieldNoDemand('REASON_MARKED', $fields['REASON_MARKED']);
			}

			$r = Internals\ShipmentTable::add($fields);
			if (!$r->isSuccess())
			{
				OrderHistory::addAction(
					'SHIPMENT',
					$order->getId(),
					'SHIPMENT_ADD_ERROR',
					null,
					$this,
					array("ERROR" => $r->getErrorMessages())
				);

				$result->addErrors($r->getErrors());
				return $result;
			}

			if ($resultData = $r->getData())
				$result->setData($resultData);

			$id = $r->getId();
			$this->setFieldNoDemand('ID', $id);

			$this->setAccountNumber($id);

			if ($order->getId() > 0 && !$this->isSystem() && $isChanged)
			{
				OrderHistory::addAction(
					'SHIPMENT',
					$order->getId(),
					'SHIPMENT_ADDED',
					$id,
					$this
				);
			}
		}

		if (!empty($fields['ALLOW_DELIVERY']) && (($isNew && $fields['ALLOW_DELIVERY'] == "Y") || !$isNew))
		{
			/** @var Main\Event $event */
			$event = new Main\Event('sale', EventActions::EVENT_ON_SHIPMENT_ALLOW_DELIVERY, array(
				'ENTITY' => $this,
				'VALUES' => $oldEntityValues,
			));
			$event->send();

			Notify::callNotify($shipment, EventActions::EVENT_ON_SHIPMENT_ALLOW_DELIVERY);
		}

		if (!empty($fields['DEDUCTED']) && (($isNew && $fields['DEDUCTED'] == "Y") || !$isNew))
		{
			/** @var Main\Event $event */
			$event = new Main\Event('sale', EventActions::EVENT_ON_SHIPMENT_DEDUCTED, array(
				'ENTITY' => $this,
				'VALUES' => $oldEntityValues,
			));
			$event->send();

			Notify::callNotify($shipment, EventActions::EVENT_ON_SHIPMENT_DEDUCTED);
		}

		if ($id > 0)
		{
			$result->setId($id);
		}

		if($result->isSuccess() && !$this->isSystem())
		{
			$this->saveExtraServices();
			$this->saveStoreId();
		}

		if ($result->isSuccess())
		{
			$changedKeys = $this->fields->getChangedKeys();
			if (in_array("DEDUCTED", $changedKeys))
			{
				$originalValues = $this->fields->getOriginalValues();
				if ($originalValues["DEDUCTED"] != $this->getField("DEDUCTED"))
				{
					/** @var PaymentCollection $col */
					$col = $this->getCollection();
					Cashbox\Internals\Pool::addDoc($col->getOrder()->getInternalId(), $this);
				}
			}
		}

                if ($this->isChanged() && self::$eventClassName)
		{
			/** @var Main\Event $event */
			$event = new Main\Event('sale', 'On'.self::$eventClassName.'EntitySaved', array(
					'ENTITY' => $this,
					'VALUES' => $this->fields->getOriginalValues(),
			));
			$event->send();
		}

		if (($eventList = Internals\EventsPool::getEvents('s'.$this->getInternalIndex())) && !empty($eventList) && is_array($eventList))
		{
			foreach ($eventList as $eventName => $eventData)
			{
				$event = new Main\Event('sale', $eventName, $eventData);
				$event->send();

				Notify::callNotify($this, $eventName);
			}

			Internals\EventsPool::resetEvents('s'.$this->getInternalIndex());
		}

		/** @var ShipmentItemCollection $shipmentItemCollection */
		if (!$shipmentItemCollection = $this->getShipmentItemCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentItemCollection" not found');
		}

		$r = $shipmentItemCollection->save();
		if (!$r->isSuccess())
			$result->addErrors($r->getErrors());

		if ($result->isSuccess())
		{
			if (!$this->isSystem())
				OrderHistory::collectEntityFields('SHIPMENT', $order->getId(), $id);
		}

		return $result;
	}

	/**
	 * @internal
	 * @return bool|int
	 */
	public function getParentOrderId()
	{
		$order = $this->getParentOrder();
		if (!$order)
		{
			return false;
		}

		return $order->getId();
	}

	/**
	 * @internal
	 * @return Order
	 * @throws Main\ObjectNotFoundException
	 */
	public function getParentOrder()
	{
		/** @var ShipmentCollection $collection */
		$collection = $this->getCollection();
		if (!$collection)
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentCollection" not found');
		}

		/** @var Order $order */
		$order = $collection->getOrder();
		if (!$order)
		{
			throw new Main\ObjectNotFoundException('Entity "Order" not found');
		}

		return $order;
	}

	/**
	 * @return ShipmentItemCollection
	 */
	public function getShipmentItemCollection()
	{
		if (empty($this->shipmentItemCollection))
		{
			$this->shipmentItemCollection = ShipmentItemCollection::load($this);
		}
		return $this->shipmentItemCollection;
	}

	protected function markSystem()
	{
		$this->setFieldNoDemand("SYSTEM", 'Y');
	}

	/**
	 * @param ShipmentCollection $collection
	 * @param Delivery\Services\Base $deliveryService
	 * @return Shipment
	 */
	public static function createSystem(ShipmentCollection $collection, Delivery\Services\Base $deliveryService = null)
	{
		$shipment = static::create($collection, $deliveryService);
		$shipment->markSystem();
		return $shipment;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->getField('ID');
	}

	/**
	 * @return float
	 */
	public function getPrice()
	{
		return $this->getField('PRICE_DELIVERY');
	}

	/**
	 * @return bool
	 */
	public function isCustomPrice()
	{
		return $this->getField('CUSTOM_PRICE_DELIVERY') == "Y" ? true: false;
	}

	/**
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->getField('CURRENCY');
	}

	/**
	 * @return int
	 */
	public function getDeliveryId()
	{
		return $this->getField('DELIVERY_ID');
	}

	/**
	 * @return string
	 */
	public function getDeliveryName()
	{
		return $this->getField('DELIVERY_NAME');
	}

	/**
	 * @param $orderId
	 */
	public function setOrderId($orderId)
	{
		$this->setField('ORDER_ID', $orderId);
	}

	/**
	 * @return Delivery\Services\Base
	 */
	public function getDelivery()
	{
		if ($this->deliveryService === null)
		{
			$this->deliveryService = $this->loadDeliveryService();
		}

		return $this->deliveryService;
	}

	/**
	 * @return Delivery\Services\Base
	 * @throws Main\ArgumentNullException
	 * @throws Main\SystemException
	 */
	protected function loadDeliveryService()
	{
		if ($deliveryId = $this->getDeliveryId())
		{
			$this->deliveryService = Delivery\Services\Manager::getObjectById($deliveryId);
		}

		return $this->deliveryService;
	}


	/**
	 * @return bool
	 */
	public function isSystem()
	{
		return $this->getField('SYSTEM') === 'Y';
	}

	/** @return bool */
	public function isCanceled()
	{
		return $this->getField('CANCELED') === 'Y';
	}

	/**
	 * @return bool
	 */
	public function isShipped()
	{
		return $this->getField('DEDUCTED') === 'Y';
	}

	/**
	 * @return Main\Type\DateTime
	 */
	public function getShippedDate()
	{
		return $this->getField('DATE_DEDUCTED');
	}

	/**
	 * @return int
	 */
	public function getShippedUserId()
	{
		return $this->getField('EMP_DEDUCTED_ID');
	}

	/**
	 * @return string
	 */
	public function getUnshipReason()
	{
		return $this->getField('REASON_UNDO_DEDUCTED');
	}

	/**
	 * @return bool
	 */
	public function isMarked()
	{
		return ($this->getField('MARKED') == "Y");
	}

	/**
	 * @return bool
	 */
	public function isReserved()
	{
		return ($this->getField('RESERVED') == "Y");
	}

	/**
	 * @return bool
	 */
	public function isAllowDelivery()
	{
		return ($this->getField('ALLOW_DELIVERY') == "Y");
	}

	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		/** @var ShipmentItemCollection $shipmentItemCollection */
		if (!$shipmentItemCollection = $this->getShipmentItemCollection())
			return true;

		return $shipmentItemCollection->isEmpty();
	}

	/**
	 * @return Main\Type\DateTime
	 */
	public function getAllowDeliveryDate()
	{
		return $this->getField('DATE_ALLOW_DELIVERY');
	}

	/**
	 * @return int
	 */
	public function getAllowDeliveryUserId()
	{
		return $this->getField('EMP_ALLOW_DELIVERY_ID');
	}

	/**
	 * @return int
	 */
	public function getCompanyId()
	{
		return $this->getField('COMPANY_ID');
	}

	/**
	 * @throws Main\ArgumentException
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\NotSupportedException
	 * @throws \Exception
	 */
	public function tryReserve()
	{
		return Internals\Catalog\Provider::tryReserveShipment($this);
	}

	/**
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\NotSupportedException
	 * @throws \Exception
	 */
	public function tryUnreserve()
	{
		return Internals\Catalog\Provider::tryUnreserveShipment($this);
	}

	/**
	 * @return Result
	 * @throws Main\NotSupportedException
	 * @throws Main\ObjectNotFoundException
	 */
	public function tryShip()
	{
		$result = new Result();

		/** @var Result $r */
		$r = Internals\Catalog\Provider::tryShipShipment($this);
		if ($r->isSuccess())
		{
			$resultList = $r->getData();

			if (!empty($resultList) && is_array($resultList))
			{
				/** @var Result $resultDat */
				foreach ($resultList as $resultDat)
				{
					if (!$resultDat->isSuccess())
					{
						$result->addErrors( $resultDat->getErrors() );
					}
				}
			}
		}
		else
		{
			$result->addErrors( $r->getErrors() );
		}

		if ($r->hasWarnings())
		{
			$result->addWarnings( $r->getWarnings() );
		}
		return $result;
	}

	/**
	 * @return Result
	 * @throws Main\NotSupportedException
	 * @throws Main\ObjectNotFoundException
	 */
	public function tryUnship()
	{
		return $this->tryShip();
	}

	/**
	 *
	 */
	public function needShip()
	{
		$changedFields = $this->fields->getChangedValues();

		if (isset($changedFields['DEDUCTED']))
		{
			if ($changedFields['DEDUCTED'] == "Y")
			{
				return true;
			}
			elseif ($changedFields['DEDUCTED'] == "N" && $this->getId() != 0)
			{
				return false;
			}
		}

		return null;
	}

	/**
	 *
	 */
	public function needDeliver()
	{
		$changedFields = $this->fields->getChangedValues();

		if (isset($changedFields['ALLOW_DELIVERY']))
		{
			if ($changedFields['ALLOW_DELIVERY'] == "Y")
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		return null;
	}

	/**
	 * @return Result
	 * @throws Main\NotSupportedException
	 * @throws Main\ObjectNotFoundException
	 */
	public function deliver()
	{
		$order = $this->getParentOrder();
		if (!$order)
		{
			throw new Main\ObjectNotFoundException('Entity "Order" not found');
		}

		$result = new Result();

		$context = array(
			'USER_ID' => $order->getUserId(),
			'SITE_ID' => $order->getSiteId(),
		);

		$creator = Internals\ProviderCreator::create($context);

		$shipmentItemCollection = $this->getShipmentItemCollection();

		/** @var ShipmentItemCollection $shipmentItemCollection */
		if (!$shipmentItemCollection)
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentItemCollection" not found');
		}

		/** @var ShipmentItem $shipmentItem */
		foreach ($shipmentItemCollection as $shipmentItem)
		{
			$creator->addShipmentItem($shipmentItem);
		}

		$r = $creator->deliver();
		if ($r->isSuccess())
		{
			$r = $creator->createItemsResultAfterDeliver($r);
			if ($r->isSuccess())
			{
				$data = $r->getData();
				if (array_key_exists('RESULT_AFTER_DELIVER_LIST', $data))
				{
					$resultList = $data['RESULT_AFTER_DELIVER_LIST'];
				}
			}
		}
		else
		{
			$result->addErrors($r->getErrors());
		}

		if (!empty($resultList) && is_array($resultList))
		{
			Recurring::repeat($order, $resultList);
		}

		return $result;
	}

	/**
	 * @return Result
	 * @throws Main\NotSupportedException
	 */
	public function allowDelivery()
	{
		return $this->setField('ALLOW_DELIVERY', "Y");
	}

	/**
	 * @return Result
	 * @throws Main\NotSupportedException
	 */
	public function disallowDelivery()
	{
		return $this->setField('ALLOW_DELIVERY', "N");
	}

	/**
	 * @param $action
	 * @param BasketItem $basketItem
	 * @param null $name
	 * @param null $oldValue
	 * @param null $value
	 * @return Result
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\NotImplementedException
	 * @throws Main\NotSupportedException
	 */
	public function onBasketModify($action, BasketItem $basketItem, $name = null, $oldValue = null, $value = null)
	{
		if (!$this->isSystem())
			throw new Main\NotSupportedException();

		if ($action !== EventActions::UPDATE)
			throw new Main\NotImplementedException();

		if ($name == "QUANTITY")
		{
			return $this->syncQuantityAfterModify($basketItem, $value, $oldValue);
		}

		return new Result();
	}

	/**
	 * @param string $name
	 * @param mixed $oldValue
	 * @param mixed $value
	 * @return Result
	 * @throws Main\NotSupportedException
	 */
	protected function onFieldModify($name, $oldValue, $value)
	{
		global $USER;

		$result = new Result();

		if ($name == "MARKED")
		{
			if ($oldValue != "Y")
			{
				$this->setField('DATE_MARKED', new Main\Type\DateTime());
				$this->setField('EMP_MARKED_ID', $USER->GetID());
			}
			elseif ($value == "N")
			{
				$this->setField('REASON_MARKED', '');
			}

		}
		elseif ($name == "ALLOW_DELIVERY")
		{
			if ($oldValue != $value)
			{
				$this->setField('DATE_ALLOW_DELIVERY', new Main\Type\DateTime());
				$this->setField('EMP_ALLOW_DELIVERY_ID', $USER->GetID());
			}

			if ($oldValue === 'N')
			{
				$shipmentStatus = Main\Config\Option::get('sale', 'shipment_status_on_allow_delivery', '');

				if (strval($shipmentStatus) != '' && $this->getField('STATUS_ID') != DeliveryStatus::getFinalStatus())
				{
					$r = $this->setStatus($shipmentStatus);
					if (!$r->isSuccess())
					{
						$result->addErrors($r->getErrors());
					}
				}
			}
		}
		elseif ($name == "DEDUCTED")
		{
			if ($oldValue != $value)
			{
				$this->setField('DATE_DEDUCTED', new Main\Type\DateTime());
				$this->setField('EMP_DEDUCTED_ID', $USER->GetID());
			}

			if ($oldValue === 'N')
			{
				$shipmentStatus = Main\Config\Option::get('sale', 'shipment_status_on_shipped', '');

				if (strval($shipmentStatus) != '' && $this->getField('STATUS_ID') != DeliveryStatus::getFinalStatus())
				{
					$r = $this->setStatus($shipmentStatus);
					if (!$r->isSuccess())
					{
						$result->addErrors($r->getErrors());
					}
				}
			}

//			if ($oldValue != $value && $value == "Y")
//			{
//				/** @var PaymentCollection $col */
//				$col = $this->getCollection();
//				Cashbox\Internals\Pool::addDoc($col->getOrder()->getInternalId(), $this);
//			}
		}
		elseif ($name == "STATUS_ID")
		{

			$event = new Main\Event('sale', EventActions::EVENT_ON_BEFORE_SHIPMENT_STATUS_CHANGE, array(
				'ENTITY' => $this,
				'VALUE' => $value,
				'OLD_VALUE' => $oldValue,
			));
			$event->send();

			Internals\EventsPool::addEvent('s'.$this->getInternalIndex(), EventActions::EVENT_ON_SHIPMENT_STATUS_CHANGE, array(
				'ENTITY' => $this,
				'VALUE' => $value,
				'OLD_VALUE' => $oldValue,
			));

			Internals\EventsPool::addEvent('s'.$this->getInternalIndex(), EventActions::EVENT_ON_SHIPMENT_STATUS_CHANGE_SEND_MAIL, array(
				'ENTITY' => $this,
				'VALUE' => $value,
				'OLD_VALUE' => $oldValue,
			));
		}


		$r = parent::onFieldModify($name, $oldValue, $value);
		if (!$r->isSuccess())
		{
			$result->addErrors($r->getErrors());
		}

		if ($r->hasWarnings())
		{
			$result->addWarnings($r->getWarnings());
		}

		if (($resultData = $r->getData()) && !empty($resultData))
		{
			$result->addData($resultData);
		}

		return $result;
	}

	/**
	 * @internal
	 *
	 * @param BasketItem $basketItem
	 * @param null $value
	 * @param null $oldValue
	 *
	 * @return Result
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\ObjectNotFoundException
	 */
	public function syncQuantityAfterModify(BasketItem $basketItem, $value = null, $oldValue = null)
	{
		$result = new Result();

		/** @var ShipmentItemCollection $shipmentItemCollection */
		if (!$shipmentItemCollection = $this->getShipmentItemCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentItemCollection" not found');
		}

		$shipmentItem = $shipmentItemCollection->getItemByBasketCode($basketItem->getBasketCode());
		if ($shipmentItem === null)
		{
			if ($value == 0)
			{
				return $result;
			}

			$shipmentItem = $shipmentItemCollection->createItem($basketItem);
		}

		$deltaQuantity = $value - $oldValue;

		if ($deltaQuantity > 0)     // plus
		{
			$shipmentItem->setFieldNoDemand(
				"QUANTITY",
				$shipmentItem->getField("QUANTITY") + $deltaQuantity
			);
			if ($this->needReservation())
				Provider::tryReserveShipmentItem($shipmentItem);
		}
		else        // minus
		{
			if (floatval($shipmentItem->getField("QUANTITY")) <= 0)
			{
				return new Result();
			}

			if ($value != 0 && roundEx($shipmentItem->getField("QUANTITY"), SALE_VALUE_PRECISION) < roundEx(-$deltaQuantity, SALE_VALUE_PRECISION))
			{
				$result->addError(
					new ResultError(
						str_replace(
							array("#NAME#", "#QUANTITY#", "#DELTA_QUANTITY#"),
							array($basketItem->getField("NAME"), $shipmentItem->getField("QUANTITY"), abs($deltaQuantity)),
							Loc::getMessage('SALE_SHIPMENT_SYSTEM_QUANTITY_ERROR')
						),
						'SALE_SHIPMENT_SYSTEM_QUANTITY_ERROR'
					)
				);
				return $result;
			}


			if($value > 0)
			{
				$shipmentItem->setFieldNoDemand(
					"QUANTITY",
					$shipmentItem->getField("QUANTITY") + $deltaQuantity
				);
				if ($this->needReservation())
					Provider::tryReserveShipmentItem($shipmentItem);
			}
			else
			{
				$shipmentItem->setFieldNoDemand("QUANTITY", 0);
			}

		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function getServiceParams()
	{
		$params = $this->getField('PARAMS');
		return isset($params["SERVICE_PARAMS"]) ? $params["SERVICE_PARAMS"] : array();
	}

	/**
	 * @param array $serviceParams
	 * @throws Main\NotSupportedException
	 */
	public function setServiceParams(array $serviceParams)
	{
		$params = $this->getField('PARAMS');
		$params["SERVICE_PARAMS"] = $serviceParams;
		$this->setField("PARAMS", $params);
	}

	public function getExtraServices()
	{
		if($this->extraServices === null)
		{
			$this->setExtraServices(
				Delivery\ExtraServices\Manager::getValuesForShipment(
					$this->getId(),
					$this->getDeliveryId()
				)
			);
		}

		return $this->extraServices;
	}

	public function setExtraServices(array $extraServices)
	{
		$this->extraServices = $extraServices;
	}

	public function saveExtraServices()
	{
		return Delivery\ExtraServices\Manager::saveValuesForShipment($this->getId(), $this->getExtraServices());
	}

	public function getStoreId()
	{
		if($this->storeId <= 0)
		{
			$this->setStoreId(
				Delivery\ExtraServices\Manager::getStoreIdForShipment(
					$this->getId(),
					$this->getDeliveryId()
				)
			);
		}

		return $this->storeId;
	}

	public function setStoreId($storeId)
	{
		$this->storeId = $storeId;
	}

	public function saveStoreId()
	{
		return Delivery\ExtraServices\Manager::saveStoreIdForShipment($this->getId(), $this->getDeliveryId(), $this->getStoreId());
	}

	/**
	 * @return float|int
	 * @throws Main\ObjectNotFoundException
	 */
	public function getWeight()
	{
		$weight = 0;
		/** @var ShipmentItemCollection $shipmentItemCollection */
		if ($shipmentItemCollection = $this->getShipmentItemCollection())
		{
			/** @var ShipmentItem $shipmentItem */
			foreach ($shipmentItemCollection->getShippableItems() as $shipmentItem)
			{
				/** @var BasketItem $basketItem */
				if (!$basketItem = $shipmentItem->getBasketItem())
				{
					continue;
				}

				$weight += $basketItem->getWeight() * $shipmentItem->getQuantity();
			}
		}

		return $weight;
	}


	/**
	 * @return Delivery\CalculationResult
	 * @throws Main\NotSupportedException
	 */
	public function calculateDelivery()
	{
		if ($this->isSystem())
		{
			throw new Main\NotSupportedException();
		}

		if ($this->getDeliveryId() == 0)
		{
			return new Delivery\CalculationResult();
		}

		/** @var Delivery\CalculationResult $deliveryCalculate */
		$deliveryCalculate =  Delivery\Services\Manager::calculateDeliveryPrice($this);
		if (!$deliveryCalculate->isSuccess())
		{
			return $deliveryCalculate;
		}

		$data = $deliveryCalculate->getData();
		$deliveryCalculate->setData($data);

		return $deliveryCalculate;
	}


	/**
	 *
	 */
	public function resetData()
	{
		$this->setFieldNoDemand('PRICE_DELIVERY', 0);

		if ($this->isCustomPrice())
			$basePriceDelivery = $this->getField("BASE_PRICE_DELIVERY");

		$this->setFieldNoDemand('BASE_PRICE_DELIVERY', 0);

		if ($this->isCustomPrice())
			$this->setField('BASE_PRICE_DELIVERY', $basePriceDelivery);

	}

	/**
	 * @param BasketItem $basketItem
	 * @return float|int
	 * @throws Main\ObjectNotFoundException
	 */
	public function getBasketItemQuantity(BasketItem $basketItem)
	{
		/** @var ShipmentItemCollection $shipmntItemCollection */
		if (!$shipmentItemCollection = $this->getShipmentItemCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentItemCollection" not found');
		}

		return $shipmentItemCollection->getBasketItemQuantity($basketItem);
	}

	/**
	 * @param string $name
	 * @param null $oldValue
	 * @param null $value
	 * @throws Main\ObjectNotFoundException
	 */
	protected function addChangesToHistory($name, $oldValue = null, $value = null)
	{
		if ($this->getId() > 0 && !$this->isSystem())
		{
			/** @var ShipmentCollection $shipmentCollection */
			if (!$shipmentCollection = $this->getCollection())
			{
				throw new Main\ObjectNotFoundException('Entity "ShipmentCollection" not found');
			}

			/** @var Order $order */
			if (($order = $shipmentCollection->getOrder()) && $order->getId() > 0)
			{
				OrderHistory::addField(
					'SHIPMENT',
					$order->getId(),
					$name,
					$oldValue,
					$value,
					$this->getId(),
					$this
					);
			}
		}
	}

	/**
	 * @param BasketItem $basketItem
	 *
	 * @return bool
	 * @throws Main\ObjectNotFoundException
	 */
	public function isExistBasketItem(BasketItem $basketItem)
	{
		/** @var ShipmentItemCollection $shipmentItemCollection */
		if (!$shipmentItemCollection = $this->getShipmentItemCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentItemCollection" not found');
		}

		return $shipmentItemCollection->isExistBasketItem($basketItem);
	}


	/**
	 * @return Result
	 */
	public function verify()
	{
		$result = new Result();
		if ($this->getDeliveryId() <= 0)
		{
			$result->addError(new ResultError(Loc::getMessage("SALE_SHIPMENT_DELIVERY_SERVICE_EMPTY"), 'SALE_SHIPMENT_DELIVERY_SERVICE_EMPTY'));
		}

		/** @var ShipmentItemCollection $shipmentItemCollection */
		if ($shipmentItemCollection = $this->getShipmentItemCollection())
		{
			/** @var ShipmentItem $shipmentItem */
			foreach ($shipmentItemCollection as $shipmentItem)
			{
				$r = $shipmentItem->verify();
				if (!$r->isSuccess())
				{
					$result->addErrors($r->getErrors());
				}
			}
		}
		
		return $result;
	}

	/**
	 * @param $id
	 *
	 * @return Result
	 * @throws Main\ObjectNotFoundException
	 * @throws \Exception
	 */
	public function setAccountNumber($id)
	{
		$result = new Result();
		$accountNumber = null;
		$id = intval($id);
		if ($id <= 0)
		{
			$result->addError(new ResultError(Loc::getMessage('SALE_PAYMENT_GENERATE_ACCOUNT_NUMBER_ORDER_NUMBER_WRONG_ID'), 'SALE_PAYMENT_GENERATE_ACCOUNT_NUMBER_ORDER_NUMBER_WRONG_ID'));
			return $result;
		}

		$value = Internals\AccountNumberGenerator::generate($this);

		try
		{
			/** @var \Bitrix\Sale\Result $r */
			$r = Internals\ShipmentTable::update($id, array("ACCOUNT_NUMBER" => $value));
			$res = $r->isSuccess(true);
		}
		catch (Main\DB\SqlQueryException $exception)
		{
			$res = false;
		}

		if ($res)
		{
			if ($this->isSystem())
			{
				$this->setFieldNoDemand('ACCOUNT_NUMBER', $value);
			}
			else
			{
				$r = $this->setField('ACCOUNT_NUMBER', $value);
				if (!$r->isSuccess())
				{
					$result->addErrors($r->getErrors());
				}
			}
		}

		return $result;
	}

	public function getBusinessValueProviderInstance($mapping)
	{
		$providerInstance = null;

		if (is_array($mapping))
		{
			switch ($mapping['PROVIDER_KEY'])
			{
				case 'SHIPMENT': $providerInstance = $this; break;
				case 'COMPANY' : $providerInstance = $this->getField('COMPANY_ID'); break;
				default:
					/** @var ShipmentCollection $collection */
					if (($collection = $this->getCollection()) && ($order = $collection->getOrder()))
						$providerInstance = $order->getBusinessValueProviderInstance($mapping);
			}
		}

		return $providerInstance;
	}

	public function getPersonTypeId()
	{
		/** @var ShipmentCollection $collection */
		return ($collection = $this->getCollection()) && ($order = $collection->getOrder())
			? $order->getPersonTypeId()
			: null;
	}

	/**
	 * @param array $filter
	 *
	 * @return Main\DB\Result
	 * @throws Main\ArgumentException
	 */
	public static function getList(array $filter)
	{
		return Internals\ShipmentTable::getList($filter);
	}

	/**
	 * @internal
	 * @param \SplObjectStorage $cloneEntity
	 *
	 * @return Shipment
	 */
	public function createClone(\SplObjectStorage $cloneEntity)
	{
		if ($this->isClone() && $cloneEntity->contains($this))
		{
			return $cloneEntity[$this];
		}

		$shipmentClone = clone $this;
		$shipmentClone->isClone = true;

		/** @var Internals\Fields $fields */
		if ($fields = $this->fields)
		{
			$shipmentClone->fields = $fields->createClone($cloneEntity);
		}

		if (!$cloneEntity->contains($this))
		{
			$cloneEntity[$this] = $shipmentClone;
		}

		/** @var ShipmentItemCollection $shipmentItemCollection */
		if ($shipmentItemCollection = $this->getShipmentItemCollection())
		{
			if (!$cloneEntity->contains($shipmentItemCollection))
			{
				$cloneEntity[$shipmentItemCollection] = $shipmentItemCollection->createClone($cloneEntity);
			}

			if ($cloneEntity->contains($shipmentItemCollection))
			{
				$shipmentClone->shipmentItemCollection = $cloneEntity[$shipmentItemCollection];
			}
		}

		if ($collection = $this->getCollection())
		{
			if (!$cloneEntity->contains($collection))
			{
				$cloneEntity[$collection] = $collection->createClone($cloneEntity);
			}

			if ($cloneEntity->contains($collection))
			{
				$shipmentClone->collection = $cloneEntity[$collection];
			}
		}

		/** @var \Bitrix\Sale\Delivery\Services\Manager $deliveryService */
		
		/** @var Delivery\Services\Manager $deliveryService */
		if ($deliveryService = $this->getDelivery())
		{
			if (!$cloneEntity->contains($deliveryService))
			{
				$cloneEntity[$deliveryService] = $deliveryService->createClone($cloneEntity);
			}

			if ($cloneEntity->contains($deliveryService))
			{
				$shipmentClone->deliveryService = $cloneEntity[$deliveryService];
			}
		}

		return $shipmentClone;
	}


	/**
	 * @param $status
	 *
	 * @return Result
	 */
	protected function setStatus($status)
	{
		global $USER;

		$result = new Result();

		if ($USER && $USER->isAuthorized())
		{
			$statusesList = DeliveryStatus::getAllowedUserStatuses($USER->getID(), $this->getField('STATUS_ID'));
		}
		else
		{
			$statusesList = DeliveryStatus::getAllStatuses();
		}

		if($this->getField('STATUS_ID') != $status && array_key_exists($status, $statusesList))
		{
			/** @var Result $r */
			$r = $this->setField('STATUS_ID', $status);
			if (!$r->isSuccess())
			{
				$result->addErrors($r->getErrors());
				return $result;
			}
		}

		return $result;
	}

	/**
	 * @param $value
	 *
	 * @return string
	 */
	public function getErrorEntity($value)
	{
		$className = null;
		$errorsList = static::getAutoFixErrorsList();
		if (is_array($errorsList) && in_array($value, $errorsList))
		{
			$className = static::getClassName();
		}
		else
		{
			/** @var ShipmentItemCollection $shipmentItemCollection */
			if ($shipmentItemCollection = $this->getShipmentItemCollection())
			{
				$className = $shipmentItemCollection->getErrorEntity($value);
			}
		}

		return $className;
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	public function canAutoFixError($value)
	{
		$autoFix = false;
		$errorsList = static::getAutoFixErrorsList();
		if (is_array($errorsList) && in_array($value, $errorsList))
		{
			$autoFix = true;
		}
		else
		{
			/** @var ShipmentItemCollection $shipmentItemCollection */
			if ($shipmentItemCollection = $this->getShipmentItemCollection())
			{
				$autoFix = $shipmentItemCollection->canAutoFixError($value);
			}
		}
		
		return $autoFix;
	}

	/**
	 * @return array
	 */
	public function getAutoFixErrorsList()
	{
		return array_keys(static::getAutoFixRules());
	}

	/**
	 * @param $code
	 *
	 * @return Result
	 */
	public function tryFixError($code)
	{
		$result = new Result();

		$method = static::getFixMethod($code);
		$r = call_user_func_array($method, array($this));
		if (!$r->isSuccess())
		{
			$result->addErrors($r->getErrors());
		}
		elseif ($r->hasWarnings())
		{
			$result->addWarnings($r->getWarnings());
		}

		return $result;
	}

	protected static function getFixMethod($code)
	{
		$codeList = static::getAutoFixRules();

		if (!empty($codeList[$code]))
		{
			return $codeList[$code];
		}
		return null;
	}

	/**
	 * @param Shipment $entity
	 *
	 * @return Result
	 * @throws Main\ObjectNotFoundException
	 */
	public static function fixReserveErrors(Shipment $entity)
	{
		$result = new Result();
		/** @var ShipmentCollection $collection */
		if (!$collection = $entity->getCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentCollection" not found');
		}

		if (!$order = $collection->getOrder())
		{
			throw new Main\ObjectNotFoundException('Entity "Order" not found');
		}
		
		$r = $entity->tryReserve();
		if (!$r->isSuccess())
		{
			$result->addErrors($r->getErrors());
		}
		elseif ($r->hasWarnings())
		{
			$result->addWarnings($r->getWarnings());
		}

		return $result;
	}

	/**
	 * @param Shipment $entity
	 *
	 * @return Result
	 * @throws Main\ObjectNotFoundException
	 */
	public static function fixShipErrors(Shipment $entity)
	{
		$result = new Result();
		/** @var ShipmentCollection $collection */
		if (!$collection = $entity->getCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "ShipmentCollection" not found');
		}

		if (!$order = $collection->getOrder())
		{
			throw new Main\ObjectNotFoundException('Entity "Order" not found');
		}

		$r = $entity->setField('DEDUCTED', 'Y');
		if (!$r->isSuccess())
		{
			if (!$r->isSuccess())
			{
				$result->addErrors($r->getErrors());
			}
		}

		$r = $entity->tryShip();
		if (!$r->isSuccess())
		{
			if (!$r->isSuccess())
			{
				$result->addErrors($r->getErrors());
			}
		}

		return $result;
	}

	/**
	 * @return array
	 */
	protected static function getAutoFixRules()
	{
		return array(
			'PROVIDER_BASKET_ITEM_WRONG_AVAILABLE_QUANTITY' => array('\Bitrix\Sale\Shipment', "fixReserveErrors"),
			'SALE_PROVIDER_RESERVE_SHIPMENT_ITEM_WRONG_AVAILABLE_QUANTITY' => array('\Bitrix\Sale\Shipment', "fixReserveErrors"),
			'PROVIDER_UNRESERVE_SHIPMENT_ITEM_WRONG_AVAILABLE_QUANTITY' => array('\Bitrix\Sale\Shipment', "fixReserveErrors"),
			'SALE_PROVIDER_RESERVE_SHIPMENT_ITEM_QUANTITY_NOT_ENOUGH' => array('\Bitrix\Sale\Shipment', "fixReserveErrors"),

			'SALE_PROVIDER_SHIPMENT_SHIPPED_LESS_QUANTITY' => array('\Bitrix\Sale\Shipment', "fixShipErrors"),
			'SALE_PROVIDER_SHIPMENT_SHIPPED_MORE_QUANTITY' => array('\Bitrix\Sale\Shipment', "fixShipErrors"),
			'DDCT_DEDUCTION_QUANTITY_STORE_ERROR' => array('\Bitrix\Sale\Shipment', "fixShipErrors"),
			'SALE_PROVIDER_SHIPMENT_QUANTITY_NOT_ENOUGH' => array('\Bitrix\Sale\Shipment', "fixShipErrors"),
			'DDCT_DEDUCTION_QUANTITY_ERROR' => array('\Bitrix\Sale\Shipment', "fixShipErrors"),
		);
	}
	
	public function canMarked()
	{
		return true;
	}

	public function getMarkField()
	{
		return 'MARKED';
	}

	/**
	 * @internal
	 */
	public function clearChanged()
	{
		if ($shipmentItemCollection = $this->getShipmentItemCollection())
		{
			/** @var ShipmentItem $shipmentItem */
			foreach ($shipmentItemCollection as $shipmentItem)
			{
				$shipmentItem->clearChanged();
			}
		}
	}

	/**
	 * @return float|int
	 */
	public function getVatRate()
	{
		$vatRate = 0;

		$service = $this->getDelivery();
		if ($service)
		{
			if (!Main\Loader::includeModule('catalog'))
				return $vatRate;

			$vatId = $service->getVatId();
			if ($vatId <= 0)
				return $vatRate;

			$dbRes = VatTable::getById($vatId);
			$vatInfo = $dbRes->fetch();
			if ($vatInfo)
			{
				$vatRate = $vatInfo['RATE'] / 100;
			}
		}

		return $vatRate;
	}

	/**
	 * @return float
	 */
	public function getVatSum()
	{
		$vatRate = $this->getVatRate();
		$price = $this->getPrice() * $vatRate / (1 + $vatRate);
		
		return PriceMaths::roundPrecision($price);
	}

	/**
	 * @param $value
	 * @param bool $custom
	 *
	 * @return Result
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\NotSupportedException
	 * @throws \Exception
	 */
	public function setBasePriceDelivery($value, $custom = false)
	{
		$result = new Result();

		$r = $this->setField('CUSTOM_PRICE_DELIVERY', ($custom ? 'Y' : 'N'));
		if (!$r->isSuccess())
		{
			$result->addErrors($r->getErrors());
		}

		$r = $this->setField('BASE_PRICE_DELIVERY', $value);
		if (!$r->isSuccess())
		{
			$result->addErrors($r->getErrors());
		}

		return $result;
	}
}

