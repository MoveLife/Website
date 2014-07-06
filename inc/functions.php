<?php
	require dirname(__FILE__).'/access.php';

	class MoveLife {
		public static function password_hash($password = '') {
			return sha1('+c7eb4691e67bat138029e2e84'.sha1($password).'dc97cb57019f075+C4K31sal13');
		}

		public static function clean($string,$esc = TRUE) {
			global $mysqli;
			$string = str_replace(array(chr(160),chr(173),chr(202)),array(' ','-',''),$string);
			$string = preg_replace('/\s{2,}/',' ',$string);
			$string = trim($string);
			$string = htmlspecialchars($string);
			if($esc) {
				$string = $mysqli->real_escape_string($string);
			}
			return $string;
		}

		public static function random_char($amount = 4,$not = array()) {
			do {
				$str = self::gen_random_char($amount);
			} while(in_array($str,$not));
			return $str;
		}

		private static function gen_random_char($amount) {
			$chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
			$length = count($chars)-1;
			$str = '';
			for($n=0;$n < $amount;$n++) {
				$str .= $chars[mt_rand(0,$length)];
			}
			return $str;
		}

		public static function get_inc($inc) {
			global $USER,$PAGE,$mysqli,$functions;
			ob_start();
			include $inc;
			$out = ob_get_contents();
			ob_end_clean();
			return $out;
		}
		
		public static function upload_file($logo,$path) {
			$ext = strtolower(substr(strrchr($logo['name'],'.'),1));
			if(!is_uploaded_file($logo['tmp_name'])) {
				return 'Upload failed.';
			}
			if($ext != 'jpg' && $ext != 'jpeg') {
				return 'Not a JPG file.';
			}
			if(empty($logo['name']) || $logo['name'] == 'none' || $logo['size'] < 1) {
				return 'Upload failed.';
			}
			$moved = @move_uploaded_file($logo['tmp_name'],$path);
			if(!$moved) {
				return 'Upload failed.';
			}
			$img_dimensions = @getimagesize($path);
			if(!is_array($img_dimensions)) {
				return 'Upload failed.';
			}
			if($img_dimensions[0] > 600 || $img_dimensions[1] > 400) {
				@unlink($path);
				return 'Image too large.';
			}
			if($logo['size'] > 1024*200) {
				@unlink($path);
				return 'File too large.';
			}
			if(!self::mimeEquals($logo['type'],image_type_to_mime_type($img_dimensions[2]))) {
				@unlink($path);
				return 'Not a JPG file.';
			}
			return TRUE;
		}
		
		private static function mimeEquals($mime1,$mime2) {
			if($mime1 == $mime2) {
				return true;
			}
			$mime1 = explode('/',$mime1);
			$mime2 = explode('/',$mime2);
			if(count($mime1) != 2 || count($mime2) != 2) {
				return false;
			}
			$mimetypes = array(
				'x-jpg' => 'jpeg',
				'x-jpeg' => 'jpeg',
				'pjpeg' => 'jpeg',
				'jpg' => 'jpeg'
			);
			if(isset($mimetypes[$mime1[1]])) {
				$mime1[1] = $mimetypes[$mime1[1]];
			}
			if(isset($mimetypes[$mime2[1]])) {
				$mime2[1] = $mimetypes[$mime2[1]];
			}
			return $mime1[1] == $mime2[1];
		}
	}
?>