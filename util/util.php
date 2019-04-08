<?php
	class util{
		public static function sha256($s){
			return hash('sha256', $s);
		}
		
		public static function GetIpClient(){
			$ip = '';
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			return $ip;
		}
		
		public static function GetUrlRefer(){
			if(isset($_SERVER['HTTP_REFERER'])){
				return $_SERVER['HTTP_REFERER'];
			}
			return '';
		}
		
		public static function get_numeric($val) {
			if (is_numeric($val)) {
				return $val + 0;
			}
			return 0;
		}
		
		//public static function StartsWith($string, $leftString) {
		//  return substr($string, 0, strlen($leftString)) === $leftString;
		//}
		
		public static function StartsWith($haystack, $needle) {
			return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
		}
		
		public static function EndsWith($haystack, $needle) {
			return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
		}
		
		public static function CheckOnlyNumber($string){
			if(isset($string)){
				return preg_match("/^d+$/", $string);
			}
			else{
				return false;
			}
		}
		
		public static function CheckNumber($string){
			if(isset($string)){
				return preg_match("/^(-)?[0-9]+(.)?[0-9]*$/", $string);
			}
			else{
				return false;
			}
		}
		
		public static function CheckInt($string){
			if(isset($string)){
				return preg_match("/^(-)?[0-9]+$/", $string);
			}
			else{
				return false;
			}
		}
		
		public static function CheckEmail($email,$request){
			if(isset($email)){
				return preg_match("/^([a-zA-Z0-9_\\.\\-])+\\@(([a-zA-Z0-9\\-])+\\.)+([a-zA-Z0-9]{2,4})+$/", $email);
			}
			else{
				return !$request;
			}
		}
		
		public static function CheckUsername($username){
			return preg_match("/^[a-z][a-z0-9_]{2,19}$/", $username);
		}
		
		public static function CheckPassword($password){
			//return preg_match("/.{6,30}/", $password);
			if($password!=null){
				return strlen($password)>=6 && strlen($password)<=30;
			}
			return false;
		}
		
		public static function CheckNameVi($name){
			return preg_match("/[a-zA-Z àáảãạâầấẩẫậăằắẳẵặèéẻẽẹêềếểễệđìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵÀÁẢÃẠÂẦẤẨẪẬĂẰẮẲẴẶÈÉẺẼẸÊỀẾỂỄỆĐÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴÂĂĐÔƠƯ]{2,30}/", $name);
		}
		
		public static function ReplaceHTML($string){
			$string = str_replace('<', '&lt;', $string);
			return str_replace('>', '&gt;', $string);
		}

        public static function RandomNumber($length){
            $s = '0123456789';
            $strlen = strlen($s)-1;
            $sOut = '';
            for ($i = 0; $i < $length; $i++) {
                $post = rand(0, $strlen);
                $sOut .= substr($s,$post,1);
            }
            return $sOut;
        }
		
		public static function RandomString($length){
			$s = 'qertyuipasdfghjklzxcvbnm0123456789';
            $strlen = strlen($s)-1;
			$sOut = '';
			for ($i = 0; $i < $length; $i++) {
				$post = rand(0, $strlen);
				$sOut .= substr($s,$post,1);
			}
			return $sOut;
		}
		
		public static function GetDateFromStr($sDate){
			$y = substr($sDate, 0,4);
			$M = substr($sDate, 4,2);
			$d = substr($sDate, 6,2);
			$H = substr($sDate, 8,2);
			$m = substr($sDate, 10,2);
			$s = substr($sDate, 12,2);
			
			return printf("%d-%d-%d %d:%d:%d",$y,$M,$d,$H,$m,$s);
		}

		public static function TimeStampToDate($i){
            $date = new DateTime();
            $date->setTimestamp($i);
            return $date->format('Y-m-d H:i:s');
        }
		
		public static function GenPageCard($totalrecord,$irecordofpage,$pageindex,$className,$classActive,$rshow,$page){
			$numberpage = 0;
			
			if ($totalrecord % $irecordofpage == 0)
				$numberpage = intval($totalrecord / $irecordofpage);
			else
				$numberpage = intval($totalrecord / $irecordofpage) + 1;
			
			if ($numberpage == 1)
				return "";
			//echo $numberpage;//
			$loopend = 0;
			$loopstart = 0;
			$istart = false;
			$iend = false;
			if ($pageindex == 0)
			{
				$loopstart = 0;
				$loopend = $numberpage > ($rshow - 1) ? $rshow : $numberpage;
				if ($numberpage > $rshow)
					$iend = true;
			}
			else
			{
				if ($pageindex < $numberpage - ($rshow - 1) && $pageindex != 0)
				{
					$loopstart = $pageindex - 1;
					$loopend = $pageindex + ($rshow - 1);
					$iend = true;
					if ($pageindex > 1)
					{
						$istart = true;
					}
				}
				else
				{
					if ($numberpage - $rshow > 0)
					{
						$loopstart = $numberpage - $rshow;
						$istart = true;
						$loopend = $numberpage;
					}
					else
					{
						$loopstart = 0;
						$loopend = $numberpage;
					}
				}
			}
			
			$sPage = sprintf('<div class="%s">',$className);
			if ($istart)
			{
				$sPage .= sprintf('<a class="paginate_button" href="?page=%s&trang=%d">‹‹</a>',$page, 0);
			}
			if ($pageindex >= 1)
				$sPage .= sprintf('<a class="paginate_button" href="?page=%s&trang=%d" >‹</a>',$page,$pageindex - 1);
			for ($i = $loopstart; $i < $loopend; $i++)
			{
			if ($pageindex == $i)
				{
					$sPage .= sprintf('<a class="paginate_button %s" href="javascript:void(0);" >',$classActive);
				}
				else
				{
					$sPage .= sprintf('<a class="paginate_button" href="?page=%s&trang=%d">',$page, $i);
				}
				
				$sPage .= ($i+1);
				$sPage .= '</a>';
			}
			if ($pageindex <= $numberpage - 2)
			{
				$sPage .= sprintf('<a class="paginate_button next" href="?page=%s&trang=%d" >›</a>',$page, $pageindex + 1);
			}
			if ($iend) $sPage .= sprintf('<a class="paginate_button next" href="?page=%s&trang=%d" >››</a>',$page, $numberpage - 1);
			
			$sPage .= sprintf('</div>');
		
			return $sPage;
		}
		
		public static function GenPageJs($totalrecord,$irecordofpage,$pageindex,$className,$classActive,$rshow,$function_name){
			$numberpage = 0;
				
			if ($totalrecord % $irecordofpage == 0)
				$numberpage = intval($totalrecord / $irecordofpage);
			else
				$numberpage = intval($totalrecord / $irecordofpage) + 1;
				
			if ($numberpage == 1)
				return "";
			//echo $numberpage;//
			$loopend = 0;
			$loopstart = 0;
			$istart = false;
			$iend = false;
			if ($pageindex == 0)
			{
				$loopstart = 0;
				$loopend = $numberpage > ($rshow - 1) ? $rshow : $numberpage;
				if ($numberpage > $rshow)
					$iend = true;
			}
			else
			{
				if ($pageindex < $numberpage - ($rshow - 1) && $pageindex != 0)
				{
					$loopstart = $pageindex - 1;
					$loopend = $pageindex + ($rshow - 1);
					$iend = true;
					if ($pageindex > 1)
					{
						$istart = true;
					}
				}
				else
				{
					if ($numberpage - $rshow > 0)
					{
						$loopstart = $numberpage - $rshow;
						$istart = true;
						$loopend = $numberpage;
					}
					else
					{
						$loopstart = 0;
						$loopend = $numberpage;
					}
				}
			}
				
			$sPage = sprintf('<div class="%s">',$className);
			if ($istart)
			{
				$sPage .= sprintf('<a class="paginate_button" onclick="javascript:%s(%d)" href="javascript:void(0);">‹‹</a>',$function_name, 0);
			}
			if ($pageindex >= 1)
				$sPage .= sprintf('<a class="paginate_button" onclick="javascript:%s(%d)" href="javascript:void(0);">‹</a>',$function_name,$pageindex - 1);
			for ($i = $loopstart; $i < $loopend; $i++)
			{
			if ($pageindex == $i)
			{
			$sPage .= sprintf('<a class="paginate_button %s" href="javascript:void(0);" >',$classActive);
			}
			else
			{
			$sPage .= sprintf('<a class="paginate_button" onclick="javascript:%s(%d)" href="javascript:void(0);">',$function_name, $i);
			}
		
			$sPage .= ($i+1);
			$sPage .= '</a>';
		}
			if ($pageindex <= $numberpage - 2)
			{
			$sPage .= sprintf('<a class="paginate_button next" onclick="javascript:%s(%d)" href="javascript:void(0);" >›</a>',$function_name, $pageindex + 1);
			}
			if ($iend) $sPage .= sprintf('<a class="paginate_button next" onclick="javascript:%s(%d)" href="javascript:void(0);" >››</a>',$function_name, $numberpage - 1);
				
			$sPage .= sprintf('</div>');
		
			return $sPage;
		}
	}
?>