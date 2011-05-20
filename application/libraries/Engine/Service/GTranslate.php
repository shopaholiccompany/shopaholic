<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Service_GTranslate
 * @version    $Id: GTranslate.php 7262 2010-09-02 00:52:32Z john $
 */

/**
 * GTranslate
 * A class to comunicate with Google Translate(TM) Service
 * Google Translate(TM) API Wrapper
 * More info about Google(TM) service can be found on
 * http://code.google.com/apis/ajaxlanguage/documentation/reference.html
 * This code has no affiliation with Google (TM), its a PHP Library that allows
 * communication with public a API
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Jose da Silva <jose@josedasilva.net>
 * @author John Boehr <j@webligo.com>
 * @license LGPLv3 http://www.gnu.org/licenses/lgpl.html
 *
 * <code>
 * <?php
 * try {
 *  $gt = new Engine_Service_GTranslate();
 *  echo $gt->query("en", "de", "hello world");
 * } catch( Engine_Service_GTranslate_Exception $e ) {
 *  echo $e->getMessage();
 * }
 * ?>
 * </code>
 */
class Engine_Service_GTranslate
{
  /**
   * Google Translate(TM) Api endpoint
   *
   * @access protected
   * @var string
   */
  protected $_url = "http://ajax.googleapis.com/ajax/services/language/translate";

  /**
   * Google Translate (TM) Api Version
   *
   * @access protected
   * @var string
   */
  protected $_apiVersion = "1.0";

  /**
   * Comunication Transport Method
   * Available: http / curl
   * 
   * @access protected
   * @var string
   */
  protected $_requestType = "http";

  /**
   * Holder to the parse of the ini file
   * 
   * @access protected
   * @var array
   */
  static protected $_availableLanguages = array(
    'af',
    'sq',
    'am',
    'ar',
    'hy',
    'az',
    'eu',
    'be',
    'bn',
    'bh',
    'bg',
    'my',
    'ca',
    'chr',
    'zh',
    'zh_CN',
    'zh_TW',
    'hr',
    'cs',
    'da',
    'dv',
    'nl',
    'en',
    'eo',
    'et',
    'tl',
    'fi',
    'fr',
    'gl',
    'ka',
    'de',
    'el',
    'gn',
    'gu',
    'iw',
    'hi',
    'hu',
    'is',
    'id',
    'iu',
    'ga',
    'it',
    'ja',
    'kn',
    'kk',
    'km',
    'ko',
    'ku',
    'ky',
    'lo',
    'lv',
    'lt',
    'mk',
    'ms',
    'ml',
    'mt',
    'mr',
    'mn',
    'ne',
    'no',
    'or',
    'ps',
    'fa',
    'pl',
    'pt_PT',
    'pa',
    'ro',
    'ru',
    'sa',
    'sr',
    'sd',
    'si',
    'sk',
    'sl',
    'es',
    'sw',
    'sv',
    'tg',
    'ta',
    'tl',
    'te',
    'th',
    'bo',
    'tr',
    'uk',
    'ur',
    'uz',
    'ug',
    'vi',
    'cy',
    'yi',
  );

  static protected $_localeToLanguage = array(
    'he' => 'iw',
  );

  /**
   * Google Translate api key
   *
    * @access protected
   * @var string
   */
  protected $_apiKey = null;

  /**
   * Check if the specified language is supported
   * 
   * @param string $language
   * @return boolean
   */
  static public function isAvailableLanguage($language)
  {
    if( isset(self::$_localeToLanguage[$language]) ) {
      $language = self::$_localeToLanguage[$language];
    }
    return in_array($language, self::$_availableLanguages);
  }

  /**
   * Get available languages
   *
   * @param boolean $translateLocales
   * @return string
   */
  static public function getAvailableLanguages($translateLocales = true)
  {
    if( !$translateLocales ) {
      return self::$_availableLanguages;
    } else {
      $availableLanguages = array();
      $reverseTranslate = array_flip(self::$_localeToLanguage);
      foreach( self::$_availableLanguages as $availableLanguage ) {
        if( isset($reverseTranslate[$availableLanguage]) ) {
          $availableLanguage = $reverseTranslate[$availableLanguage];
        }
        $availableLanguages[] = $availableLanguage;
      }
      return $availableLanguages;
    }
  }


  
  /**
   * Define the request type
   * 
   * @access public
   * @param string $type
   * @return self
   */
  public function setRequestType($type = 'http')
  {
    $method = '_request' . ucfirst($type);
    if( !method_exists($this, $method) ) {
      throw new Engine_Service_GTranslate_Exception('Request type is not available');
    }
    $this->_requestType = $type;
    return $this;
  }

  /**
   * Define the Google Translate Api Key
   * 
    * @access public
   * @param string $key
   * @return self
   */
  public function setApiKey($key)
  {
    $this->_apiKey = $key;
    return $this;
  }

  /**
   * Query the Google(TM) endpoint
   * 
   * @access protected
   * @param string $from Locale to translate from
   * @param string $to Locale to translate to
   * @param string|array $message The string to translate
   * @return string|array $translatedMessage
   * @throws Engine_Service_GTranslate_Exception On invalid parameters
   */
  public function query($from, $to, $message)
  {
    // Convert to google-locales
    if( isset(self::$_localeToLanguage[$from]) ) {
      $from = self::$_localeToLanguage[$from];
    }
    if( isset(self::$_localeToLanguage[$to]) ) {
      $to = self::$_localeToLanguage[$to];
    }

    // Validate args
    if( !is_string($from) || !self::isAvailableLanguage($from) ) {
      throw new Engine_Service_GTranslate_Exception('"From" language not available.');
    }
    if( !is_string($to) || !self::isAvailableLanguage($to) ) {
      throw new Engine_Service_GTranslate_Exception('"To" language not available.');
    }
    if( empty($message) || (!is_string($message) && !is_array($message)) ) {
      throw new Engine_Service_GTranslate_Exception('No message provided or invalid data type.');
    }
    if( is_array($message) && array_sum(array_map('is_string', $message)) != count($message) ) {
      throw new Engine_Service_GTranslate_Exception('Invalid data type given for message.');
    }

    // They use - instead of _ now
    $from = str_replace('_', '-', $from);
    $to = str_replace('_', '-', $to);
    
    // Process
    $method = '_request' . ucfirst($this->_requestType);
    $response = $this->$method(array(
      'v' => $this->_apiVersion,
      'from' => $from,
      'to' => $to,
      'q' => $message,
    ));

    // Validate
    $text = $this->_processResponse($response);

    return $text;
  }



  // Request methods

  /**
   * Query Wrapper for Http Transport
   * 
   * @access protected
   * @param array $args
   * @return string $response
   */
  protected function _requestHttp($args)
  {
    $url = $this->_url . '?' . $this->_buildQuery($args);
    return Zend_Json::decode(file_get_contents($url));
  }
  
  /**
   * Query Wrapper for Curl Transport
   * 
   * @access protected
   * @param array $args
   * @return string $response
   */
  protected function _requestCurl($args)
  {
    $query = $this->_buildQuery($args);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, !empty($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    $body = curl_exec($ch);
    curl_close($ch);

    return Zend_Json::decode($body);
  }


  
  // Utility

  /**
   * Build the query
   * 
   * @param array $args
   * @return string
   */
  protected function _buildQuery($args)
  {
    $args['langpair'] = $args['from'] . '|' . $args['to'];
    unset($args['from']);
    unset($args['to']);

    $messages = null;
    if( is_array($args['q']) ) {
      $messages = $args['q'];
      unset($args['q']);
    }

    $query = http_build_query($args);

    if( is_array($messages) ) {
      foreach( $messages as $message ) {
        $query .= '&' . http_build_query(array('q' => $message));
      }
    }

    return $query;
  }

  /**
   * Response Evaluator, validates the response
   *
   * @access protected
   * @param array $response
   * @return string|array
   * @throws Engine_Service_GTranslate_Exception On error
   */
  protected function _processResponse($response)
  {
    switch( $response['responseStatus'] )
    {
      case 200:
        if( isset($response['responseData']['translatedText']) && is_string($response['responseData']['translatedText']) ) {
          return $response['responseData']['translatedText'];
        }

        if( is_array($response['responseData']) ) {
          $text = array();
          foreach( $response['responseData'] as $subResponse ) {
            $text[] = $this->_processResponse($subResponse);
          }
          return $text;
        }

        // What happened here?
        throw new Engine_Service_GTranslate_Exception("Unable to perform translation: something weird happened");
        
        break;
      default:
        throw new Engine_Service_GTranslate_Exception("Unable to perform translation: " . $response['responseDetails']);
      break;
    }
  }
}
