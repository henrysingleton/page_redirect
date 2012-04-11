<?php
	
	class extension_page_redirect extends Extension {
		
		private $redirectTrigger;
		
		public function about() {
			return array(
				'name'			=> 'Page Redirect',
				'version'		=> '1.0',
				'release-date'	=> '2012-04-11',
				'author'		=> array(
					'name'			=> 'Henry Singleton',
					'website'		=> 'http://henrysingleton.com'
				),
				'description' => 'Redirect to a URL generated via page content for 301 page types.'
			);
		}
		
		public function uninstall() {
			return true;
		}
		
		public function install() {
			return true;
		}
		
		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/frontend/',
					'delegate'	=> 'FrontendOutputPostGenerate',
					'callback'	=> 'scanRedirect'
				),
				array(
					'page'		=> '/frontend/',
					'delegate'	=> 'FrontendPageResolved',
					'callback'	=> 'checkRedirectPage'
				)
			);
		}
		
		public function checkRedirectPage($page) {
			if (is_array($page) &&
				is_array($page['page_data']) &&
				array_key_exists('type', $page['page_data']) &&
				array_search('301',$page['page_data']['type']) !== false
			) {
					$this->redirectTrigger = true;
			}
		}
		
		public function scanRedirect($page) {
			if ($this->redirectTrigger !== true) return;
			$content = $page['output'];
			if (
					$content &&
					strpos($content, '<') !== 0 && 
					preg_match('/^[^\s:\/?#]+:(?:\/{2,3})?[^\s.\/?#]+(?:\.[^\s.\/?#]+)*(?:\/[^\s?#]*\??[^\s?#]*(#[^\s#]*)?)?$/', $content)
				) {
				header("Location: ".$content,TRUE,301);
			}
		}		
				
	}