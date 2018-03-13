<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<link href="0.css" type="text/css"  rel="stylesheet" />
<?/*
if(isset($_GET['commit'])&&isset($_GET['filename'])) {
	exec('git show '.$_GET['commit'], $all);
	$start=0;
	foreach ($all as $k => $v) {
		if(strpos($v, 'iff --git ')===1) {
			$filename=substr(str_replace('diff --git ', '', $v), 0, strpos(str_replace('diff --git ', '', $v), ' '));
			if($filename==$_GET['filename']) $start=1;
			elseif($start==1) break;
		}
		elseif($start==1) {
			echo "<div class='code'>";
			echo "<span class='".(strpos($v, '-')===0?'red':(strpos($v, '+')===0?'green':(strpos($v, '@@')===0?'bb':'')))."'>".htmlspecialchars($v)."</span><br>";
			echo "</div>";
		}
	}
}
else {?>
	<p><a href="" data-clean>Очистить лог файл</a> (<a href="/git/0.txt" target="_blank">см</a>)</p>
	<?
	exec('git log', $output);
	$num=-1;
	foreach ($output as $key => $value) {
		if(strpos($value, 'ommit')===1) {
			$num++;
			$commit[$num]['id']= str_replace('commit ', '', $value);
		}
		elseif(strpos($value, 'ate: ')===1) $commit[$num]['date']= str_replace('Date: ', '', date('d.m.Y H:i:s', strtotime(substr($value, 7, -6))));
		elseif(strpos($value, 'Author: ')===false&&$value!='') $commit[$num]['name']= $value;
	}
	foreach ($commit as $key => $value) {
		$all=array();
		$files=array();
		echo "<p data-name='".$value['date']." ".$value['name']."' data-ud='".$value['id']."'>".$value['date']."&nbsp;<a href='#' class='files_commit'>".$value['name']."</a></p>";
		echo "<form><p class='files_in_commit'><br><button class='send_file' type='submit'>Отправить</button></p></form>";
		
		if($key==100) break;	
	}
} ?>
<script src="https://yastatic.net/jquery/3.1.1/jquery.js"></script>
<script>
$(function () {
    $(document).on('click',  '[data-clean]', function(e) {
    	e.preventDefault();
    	$.ajax({
            type: "POST",
            url: '/git/1.php?clean=true',  
            success: function(data) { 
                alert(data);
            }
        });
    });
    $(document).on('click',  '.files_commit', function(e) {
    	e.preventDefault();
    	$this = $(this).closest('p').next('form').find('.files_in_commit');
    	if($this.find('[type=checkbox]').length==0) $.ajax({
            type: "POST",
            url: '/git/1.php?getcommit=true&commit='+$(this).closest('[data-ud]').data('ud'),             
            success: function(data) { 
			console.log(data);
                $this.prepend(data);
            }
        });
        $(this).closest('p').next('form').find('.files_in_commit').slideToggle();
    });
    $(document).on('submit',  'form', function(e) {
    	$this = $(this);
        //var s = $this.serialize();
        var s =  {};
        $(this).find('[type=checkbox]:checked').each(function(i, thix) {
        	s[i+1] = $(this).attr('name');
        });
        s['commit'] = $(this).prev('p').data('ud');
        s['name_commit'] = $(this).prev('p').data('name');
        $.ajax({
            type: "POST",
            url: '/git/1.php', 
            data: s, 
            success: function(data) { 
                alert(data);
            }
        });
        return false;
    });
});
</script>
<?*//*exec('git status', $all);
print_r($all);*/?>