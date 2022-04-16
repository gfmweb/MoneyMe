<?php
	
	class TelegramAlert extends CI_Controller
	{
		public static function send($text){
			$users=[822173207];
			$telegramToken ='5240751921:AAEdopT4mhFN55D9epipSXqsIJwyO_Z3Smc';
			foreach ($users as $user){
				file_get_contents('https://api.telegram.org/bot'.$telegramToken.'/sendMessage?chat_id='.$user.'&text='.urlencode($text));
			}
		}
		
	}