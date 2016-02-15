<?php
class ModelToolMorphy extends Model {
	
  private $instances = array();
  
  public function getRoots($words, $lang = '') {
    
    if (!$lang) {
      $lang = $this->language->get('code');
    }
    
    switch($lang) {
      case 'ru':
        $lang = 'ru_RU';
        break;
      case 'en':
        $lang = 'en_EN';
        break;
      case 'ua':
        $lang = 'uk_UA';
        break;
      default :
        $lang = 'unknown';
    }
    
    if ($lang == 'unknown') {
      return $words;
    }
    
    if (isset($this->instances[$lang])) {
      $morphy = $this->instances[$lang];
    }    
    else {
      require_once DIR_SYSTEM . '/library/phpmorphy/src/common.php';
      
      // set some options
      $opts = array(
          // storage type, follow types supported
          // PHPMORPHY_STORAGE_FILE - use file operations(fread, fseek) for dictionary access, this is very slow...
          // PHPMORPHY_STORAGE_SHM - load dictionary in shared memory(using shmop php extension), this is preferred mode
          // PHPMORPHY_STORAGE_MEM - load dict to memory each time when phpMorphy intialized, this useful when shmop ext. not activated. Speed same as for PHPMORPHY_STORAGE_SHM type
          'storage' => PHPMORPHY_STORAGE_FILE,
          // Enable prediction by suffix
          'predict_by_suffix' => true, 
          // Enable prediction by prefix
          'predict_by_db' => true,
          // TODO: comment this
          'graminfo_as_text' => true,
      );

      // Path to directory where dictionaries located
      $dir = DIR_SYSTEM . '/library/phpmorphy/dicts/' . $lang;
      
      // Create phpMorphy instance
      try {
          $morphy = new phpMorphy($dir, $lang, $opts);
      } catch(phpMorphy_Exception $e) {
          die('Error occured while creating phpMorphy instance: ' . PHP_EOL . $e);
      }
      
      $this->instances[$lang] = $morphy;      
    }

    if (!is_array($words)) {
      preg_match_all('/[[:alnum:]]{3,}/isu', stripslashes($words), $matches);
      $words=array_unique($matches[0]);
    }

    foreach ($words as &$word) {
      $word = mb_strtoupper($word, 'UTF-8');
    }
    unset($word);

    $roots = array();
    
    try {
        foreach($words as $word) {
            $pseudo_root = $morphy->getPseudoRoot($word);
            if(is_array($pseudo_root) && isset($pseudo_root[0]) && !empty($pseudo_root[0])) {
              $roots[] = $pseudo_root[0];
            }
            else {
              $roots[] = $word;
            }
        }
    } catch(phpMorphy_Exception $e) {
        die('Error occured while text processing: ' . $e->getMessage());
    }
    
    $roots = array_unique($roots);
    
    return $roots;
  }
}