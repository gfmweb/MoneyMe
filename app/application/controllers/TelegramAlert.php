<?php
	
	/**
	 * Маленький оповещатель
	 * циклично по массиву пользователей отправляет предоставленный ему текст
	 */
	class TelegramAlert extends CI_Controller
	{
		/**
		 * @param string $text
		 * @return void
		 */
		public static function send($db, string $text){
			$users = $db->select('telegram_id')->from('telegram_users')->get()->result_array();
			$telegramToken ='5240751921:AAEdopT4mhFN55D9epipSXqsIJwyO_Z3Smc';
			foreach ($users as $user){
				file_get_contents('https://api.telegram.org/bot'.$telegramToken.'/sendMessage?chat_id='.$user['telegram_id'].'&text='.urlencode($text));
			}
		}
		
	}
