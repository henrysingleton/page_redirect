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
					'delegate'	=> 'FrontendPageResolved',
					'callback'	=> 'checkRedirectPageType'
				),
				array(
					'page'		=> '/frontend/',
					'delegate'	=> 'FrontendOutputPostGenerate',
					'callback'	=> 'scanURL'
				)
			);
		}
		
		public function checkRedirectPageType($page) {
			if (is_array($page) &&
				is_array($page['page_data']) &&
				array_key_exists('type', $page['page_data']) &&
				array_search('301',$page['page_data']['type']) !== false
			) {
					$this->redirectTrigger = true;
			}
		}
		
		public function scanURL($page) {
			if ($this->redirectTrigger === true) {
				$content = $page['output'];
				if (
						strpos($content, '<') !== 0 && 
						preg_match('/^[^\s:\/?#]+:(?:\/{2,3})?[^\s.\/?#]+(?:\.[^\s.\/?#]+)*(?:\/[^\s?#]*\??[^\s?#]*(#[^\s#]*)?)?$/', $content)
					) {
					$this->addHeaderToPage('HTTP/1.1 301 Moved Permanently');
					$this->addHeaderToPage('Location: '.$content);
				}
			}
		}		
				
	}