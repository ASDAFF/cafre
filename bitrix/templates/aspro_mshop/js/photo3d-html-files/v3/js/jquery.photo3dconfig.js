jQuery(document).ready(function () {
	jQuery('.photo3d-preview-image').Photo3Dconfig();
});
(function ($) {
	var methods = {
		init: function (options) {
			this.each(function () {
				if (typeof ($(this).metadata().startImmediately) != "undefined" && !$(this).metadata().startImmediately) {
					methods.click3d(this);
				} else methods.show3d(this);
			});
		},
		show3d: function (imgObj) {
			var img = $(imgObj),
				imgWidth = imgObj.width,
				imgHeight = imgObj.height,
				sprite = true,
				spriteCols = 0,
				spriteRows = 0,
				domain = '', // Specify only if 3D images are loading from different domain ('http://yourdomain.com/');
				imageLow = '',
				imageHigh = '',
				frames = 0,
				yframes = 1, 
				autoPlay = true,
				//immediate = true,
				maxZoom = 100,
				imageName = $(imgObj).attr('src').replace(domain, '').replace('.jpg', ''),
				path = '/upload/iblock/' + imageName + '.3d/',
				spath = imageName.split('/'),
				imageName = spath[spath.length - 1],
				buttonsKit = 1,
				toRotate = 1,
				zoom = 10,
				zoomIncrement = 0,
				trendCorrect = 1, // ��������� ����������� ��������, 0 �������� �� ���������, 1 � �������� �����������
				formButtons = "20px 5px", // ����������� ��� border-radius � CSS, http://htmlbook.ru/css/border-radius
				colorButtons ='#000', // ����������� ����
				sensitivity = 12 // ���������������� ����� ��� ����������� �������� ��� ������ ��� ��������������
				
				;
			if (imgObj.id == '') imgObj.id = 'id' + new Date().getTime();
			if (typeof (img.metadata().frames) != "undefined") frames = img.metadata().frames;
			if (typeof (img.metadata().yframes) != "undefined") yframes = img.metadata().yframes;
			if (typeof (img.metadata().autoPlay) != "undefined") autoPlay = img.metadata().autoPlay;
			if (typeof (img.metadata().maxZoom) != "undefined") maxZoom = img.metadata().maxZoom;
			if (typeof (img.metadata().useSeparateFrames) != "undefined" && img.metadata().useSeparateFrames) sprite = false;
			if (sprite) {
				if (typeof (img.metadata().spriteCols) != "undefined" && typeof (img.metadata().spriteRows) != "undefined") {
					spriteCols = img.metadata().spriteCols;
					spriteRows = img.metadata().spriteRows;
				}
				imageLow = domain + path + imageName + '.3d.100.jpg';
				imageHigh = domain + path + imageName + '.3d.' + maxZoom + '.jpg';
			}
			// ����� ��������:
			if (typeof (img.metadata().buttonsKit) != "undefined") buttonsKit = img.metadata().buttonsKit;
			if (typeof (img.metadata().toRotate) != "undefined") toRotate = img.metadata().toRotate;
			if (typeof (img.metadata().zoom) != "undefined") zoom = img.metadata().zoom;
			if (typeof (img.metadata().zoomIncrement) != "undefined") zoomIncrement = img.metadata().zoomIncrement;
			
			// ����� �������� 2:
			if (typeof (img.metadata().trendCorrect) != "undefined") trendCorrect = img.metadata().trendCorrect;
			if (typeof (img.metadata().formButtons) != "undefined") formButtons = img.metadata().formButtons;
			if (typeof (img.metadata().colorButtons) != "undefined") colorButtons = img.metadata().colorButtons;
			if (typeof (img.metadata().sensitivity) != "undefined") sensitivity = img.metadata().sensitivity;
			
			
			var moveKey = ''; // �� ���������� ������, ���� ��� ����
			if (maxZoom > 100) moveKey = 'move';
			
			var PANELS = [
				[
					["horizon", "bottom", "center"],
					["backward", "stop", "forward", "expand", moveKey],
					"main-control-panel"
				]
			];
				
			if (maxZoom > 100) { // ������ � �������� + - �� ���������� ���� ��� ����
				var DOP = [
					[
						["vertical", "top", "left"],
						["plus", "minus"] //,"scissors","paint-format"]
					]
				];
				jQuery.merge(PANELS, DOP);
			}
			if (!sprite && yframes > 1) { // ������ � �������� ����� ���� �� ���������� ���� ����������� � ������� ��� ����� ����� ������ 2
				var DOP = [
					[
						["vertical", "top", "right"],
						["up", "down"] //,"scissors","paint-format"]
					]
				];
				jQuery.merge(PANELS, DOP);
			}
			img.Photo3D({
				'buttonsKit': buttonsKit,
				'PANELS': PANELS,
				'imageObj': imgObj,
				'imageJqObj': img,
				'imageId': imgObj.id,
				'imgWidth': imgWidth,
				'imgHeight': imgHeight,
				'sprite': sprite,
				'frames': frames, // ���������� ������ �� ������ / frames on row
				'rows': yframes, // ����� ����� / number of rows
				'imagePath': domain + path,
				'imageName': imageName + '.3d.',
				'maxZoom': maxZoom,
				'imageLow': imageLow, // ����������� �1 (��������� ����������) "������"
				'imageHigh': imageHigh, // ����������� �2 (������� ����������) "������" 
				'imageCols': spriteCols, // ���������� ������� ����������� � "�������"
				'imageRows': spriteRows, // ���������� ����� ����������� � "�������"
				'imageFrames': frames, // ���������� ����������� � "�������"
				//'frame'        : 1, // ��������� ����
				//'row'          : 1, // ��������� ������
				'toRotate': toRotate, // 1 - ������ , 0 - �����
				'autoPlay': autoPlay, // �������� �������� ��� �������� �� (true) / ��� (false)
				'zoom': zoom, // ���������� ����� ���� (�� ��������� 10)
				'zoomStep': zoomIncrement, // ������ ���� ����: 0 - ���� ,������ 0 - ���������������� ������ ����
				'trendCorrect': trendCorrect, // ��������� ����������� ��������, �� ��������� 0, 1 � �������� �����������
				'formButtons': formButtons, // ����������� ��� border-radius � CSS, http://htmlbook.ru/css/border-radius
				'colorButtons': colorButtons, // ����������� ����
				'sensitivity': sensitivity, // ���������������� ����� ��� ����������� �������� ��� ������ ��� ��������������
			});
		},
		click3d: function (imgObj) {
			var iO = $(imgObj),
				w = imgObj.width + 'px',
				h = imgObj.height + 'px';
			iO.wrap('<span id="sw' + imgObj.id + '" style="width:' + w + ';height:' + h + ';display:inline-block;position:relative;cursor:pointer;"></span>');
			iO.before('<span id="text' + imgObj.id + '" style="position:absolute;margin:20px;">Click here to load 3D-image...</span>');
			iO.on('click', function (event) {
				$('#text' + imgObj.id).remove();
				iO.unwrap();
				iO.unbind();
				methods.show3d(this);
				event.stopPropagation();
				event.preventDefault();
			});
		}
	};
	$.fn.Photo3Dconfig = function (method) {
		// ������ ������ ������
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('����� � ������ ' + method + ' �� ���������� ��� jQuery.Photo3Dconfig');
		}
	};
})(jQuery);