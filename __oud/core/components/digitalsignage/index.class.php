<?php

	abstract class DigitalSignageManagerController extends modExtraManagerController {
		/**
		 * @access public.
		 * @var Object.
		 */
		public $digitalsignage;

		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->digitalsignage = $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/');

			$this->addJavascript($this->digitalsignage->config['js_url'].'mgr/digitalsignage.js');

			$this->addHtml('<script type="text/javascript">
				Ext.onReady(function() {
					MODx.config.help_url = "'.$this->digitalsignage->getHelpUrl().'";
	
					DigitalSignage.config = '.$this->modx->toJSON(array_merge($this->digitalsignage->config, array(
						'branding_url'          => $this->digitalsignage->getBrandingUrl(),
						'branding_url_help'     => $this->digitalsignage->getHelpUrl()
					))).';
				});
			</script>');

			return parent::initialize();
		}

		/**
		 * @access public.
		 * @return Array.
		 */
		public function getLanguageTopics() {
			return $this->digitalsignage->config['lexicons'];
		}

		/**
		 * @access public.
		 * @returns Boolean.
		 */
		public function checkPermissions() {
			return $this->modx->hasPermission('digitalsignage');
		}

	}

	class IndexManagerController extends DigitalSignageManagerController {
		/**
		 * @access public.
		 * @return String.
		 */
		public static function getDefaultController() {
			return 'home';
		}
	}

?>
