<?php
// Source: config/header.php


$app = [];

$app['config'] = [
    'db' => [
        'dsn'       => 'mysql:dbname=foler;host=localhost',
        'user'      => 'foler',
        'password'  => ''
    ],
    'url' => $_SERVER['PHP_SELF'],
    'debug' => false
];



if($app['config']['debug']):
    error_reporting(E_ALL);
endif;


$app['locale'] = 'en';



// Source: classes/export/ExportInterface.php



interface ExportInterface
{
    public function export($arr, $directory, $language);
}


// Source: classes/export/PHPExport.php


class PHPExport implements ExportInterface
{
    public function export($arr, $directory, $language)
    {
        $file = $directory . DIRECTORY_SEPARATOR . $language . '.php';

        $fp = fopen($file, 'w');
        fwrite($fp, '<?php return ' . var_export($arr, true) . ';');

        fclose($fp);
    }
}


// Source: classes/export/Spyc.php


/**
 * Spyc -- A Simple PHP YAML Class.
 *
 * @version 0.5.1
 *
 * @author Vlad Andersen <vlad.andersen@gmail.com>
 * @author Chris Wanstrath <chris@ozmm.org>
 *
 * @link https://github.com/mustangostang/spyc/
 *
 * @copyright Copyright 2005-2006 Chris Wanstrath, 2006-2011 Vlad Andersen
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
if (!function_exists('spyc_load')) {
    /**
   * Parses YAML to array.
   *
   * @param string $string YAML string.
   *
   * @return array
   */
  function spyc_load($string)
  {
      return Spyc::YAMLLoadString($string);
  }
}
if (!function_exists('spyc_load_file')) {
    /**
   * Parses YAML to array.
   *
   * @param string $file Path to YAML file.
   *
   * @return array
   */
  function spyc_load_file($file)
  {
      return Spyc::YAMLLoad($file);
  }
}
if (!function_exists('spyc_dump')) {
    /**
   * Dumps array to YAML.
   *
   * @param array $data Array.
   *
   * @return string
   */
  function spyc_dump($data)
  {
      return Spyc::YAMLDump($data, false, false, true);
  }
}
/**
 * The Simple PHP YAML Class.
 *
 * This class can be used to read a YAML file and convert its contents
 * into a PHP array.  It currently supports a very limited subsection of
 * the YAML spec.
 *
 * Usage:
 * <code>
 *   $Spyc  = new Spyc;
 *   $array = $Spyc->load($file);
 * </code>
 * or:
 * <code>
 *   $array = Spyc::YAMLLoad($file);
 * </code>
 * or:
 * <code>
 *   $array = spyc_load_file($file);
 * </code>
 */
class Spyc
{
  // SETTINGS
  const REMPTY = "\0\0\0\0\0";
  /**
   * Setting this to true will force YAMLDump to enclose any string value in
   * quotes.  False by default.
   *
   * @var bool
   */
  public $setting_dump_force_quotes = false;
  /**
   * Setting this to true will forse YAMLLoad to use syck_load function when
   * possible. False by default.
   *
   * @var bool
   */
  public $setting_use_syck_is_possible = false;
  /**#@+
  * @access private
  * @var mixed
  */
  private $_dumpIndent;
    private $_dumpWordWrap;
    private $_containsGroupAnchor = false;
    private $_containsGroupAlias = false;
    private $path;
    private $result;
    private $LiteralPlaceHolder = '___YAML_Literal_Block___';
    private $SavedGroups = array();
    private $indent;
  /**
   * Path modifier that should be applied after adding current element.
   *
   * @var array
   */
  private $delayedPath = array();
  /**#@+
  * @access public
  * @var mixed
  */
  public $_nodeId;
  /**
   * Load a valid YAML string to Spyc.
   *
   * @param string $input
   *
   * @return array
   */
  public function load($input)
  {
      return $this->__loadString($input);
  }
  /**
   * Load a valid YAML file to Spyc.
   *
   * @param string $file
   *
   * @return array
   */
  public function loadFile($file)
  {
      return $this->__load($file);
  }
  /**
   * Load YAML into a PHP array statically.
   *
   * The load method, when supplied with a YAML stream (string or file),
   * will do its best to convert YAML in a file into a PHP array.  Pretty
   * simple.
   *  Usage:
   *  <code>
   *   $array = Spyc::YAMLLoad('lucky.yaml');
   *   print_r($array);
   *  </code>
   *
   * @access public
   *
   * @return array
   *
   * @param string $input Path of YAML file or string containing YAML
   */
  public static function YAMLLoad($input)
  {
      $Spyc = new Spyc();

      return $Spyc->__load($input);
  }
  /**
   * Load a string of YAML into a PHP array statically.
   *
   * The load method, when supplied with a YAML string, will do its best
   * to convert YAML in a string into a PHP array.  Pretty simple.
   *
   * Note: use this function if you don't want files from the file system
   * loaded and processed as YAML.  This is of interest to people concerned
   * about security whose input is from a string.
   *
   *  Usage:
   *  <code>
   *   $array = Spyc::YAMLLoadString("---\n0: hello world\n");
   *   print_r($array);
   *  </code>
   *
   * @access public
   *
   * @return array
   *
   * @param string $input String containing YAML
   */
  public static function YAMLLoadString($input)
  {
      $Spyc = new Spyc();

      return $Spyc->__loadString($input);
  }
  /**
   * Dump YAML from PHP array statically.
   *
   * The dump method, when supplied with an array, will do its best
   * to convert the array into friendly YAML.  Pretty simple.  Feel free to
   * save the returned string as nothing.yaml and pass it around.
   *
   * Oh, and you can decide how big the indent is and what the wordwrap
   * for folding is.  Pretty cool -- just pass in 'false' for either if
   * you want to use the default.
   *
   * Indent's default is 2 spaces, wordwrap's default is 40 characters.  And
   * you can turn off wordwrap by passing in 0.
   *
   * @access public
   *
   * @return string
   *
   * @param array $array PHP array
   * @param int $indent Pass in false to use the default, which is 2
   * @param int $wordwrap Pass in 0 for no wordwrap, false for default (40)
   * @param int $no_opening_dashes Do not start YAML file with "---\n"
   */
  public static function YAMLDump($array, $indent = false, $wordwrap = false, $no_opening_dashes = false)
  {
      $spyc = new Spyc();

      return $spyc->dump($array, $indent, $wordwrap, $no_opening_dashes);
  }
  /**
   * Dump PHP array to YAML.
   *
   * The dump method, when supplied with an array, will do its best
   * to convert the array into friendly YAML.  Pretty simple.  Feel free to
   * save the returned string as tasteful.yaml and pass it around.
   *
   * Oh, and you can decide how big the indent is and what the wordwrap
   * for folding is.  Pretty cool -- just pass in 'false' for either if
   * you want to use the default.
   *
   * Indent's default is 2 spaces, wordwrap's default is 40 characters.  And
   * you can turn off wordwrap by passing in 0.
   *
   * @access public
   *
   * @return string
   *
   * @param array $array PHP array
   * @param int $indent Pass in false to use the default, which is 2
   * @param int $wordwrap Pass in 0 for no wordwrap, false for default (40)
   */
  public function dump($array, $indent = false, $wordwrap = false, $no_opening_dashes = false)
  {
      // Dumps to some very clean YAML.  We'll have to add some more features
    // and options soon.  And better support for folding.
    // New features and options.
    if ($indent === false or !is_numeric($indent)) {
        $this->_dumpIndent = 2;
    } else {
        $this->_dumpIndent = $indent;
    }
      if ($wordwrap === false or !is_numeric($wordwrap)) {
          $this->_dumpWordWrap = 40;
      } else {
          $this->_dumpWordWrap = $wordwrap;
      }
    // New YAML document
    $string = '';
      if (!$no_opening_dashes) {
          $string = "---\n";
      }
    // Start at the base of the array and move through it.
    if ($array) {
        $array = (array) $array;
        $previous_key = -1;
        foreach ($array as $key => $value) {
            if (!isset($first_key)) {
                $first_key = $key;
            }
            $string .= $this->_yamlize($key, $value, 0, $previous_key, $first_key, $array);
            $previous_key = $key;
        }
    }

      return $string;
  }
  /**
   * Attempts to convert a key / value array item to YAML.
   *
   * @access private
   *
   * @return string
   *
   * @param $key The name of the key
   * @param $value The value of the item
   * @param $indent The indent of the current node
   */
  private function _yamlize($key, $value, $indent, $previous_key = -1, $first_key = 0, $source_array = null)
  {
      if (is_array($value)) {
          if (empty($value)) {
              return $this->_dumpNode($key, array(), $indent, $previous_key, $first_key, $source_array);
          }
      // It has children.  What to do?
      // Make it the right kind of item
      $string = $this->_dumpNode($key, self::REMPTY, $indent, $previous_key, $first_key, $source_array);
      // Add the indent
      $indent += $this->_dumpIndent;
      // Yamlize the array
      $string .= $this->_yamlizeArray($value, $indent);
      } elseif (!is_array($value)) {
          // It doesn't have children.  Yip.
      $string = $this->_dumpNode($key, $value, $indent, $previous_key, $first_key, $source_array);
      }

      return $string;
  }
  /**
   * Attempts to convert an array to YAML.
   *
   * @access private
   *
   * @return string
   *
   * @param $array The array you want to convert
   * @param $indent The indent of the current level
   */
  private function _yamlizeArray($array, $indent)
  {
      if (is_array($array)) {
          $string = '';
          $previous_key = -1;
          foreach ($array as $key => $value) {
              if (!isset($first_key)) {
                  $first_key = $key;
              }
              $string .= $this->_yamlize($key, $value, $indent, $previous_key, $first_key, $array);
              $previous_key = $key;
          }

          return $string;
      } else {
          return false;
      }
  }
  /**
   * Returns YAML from a key and a value.
   *
   * @access private
   *
   * @return string
   *
   * @param $key The name of the key
   * @param $value The value of the item
   * @param $indent The indent of the current node
   */
  private function _dumpNode($key, $value, $indent, $previous_key = -1, $first_key = 0, $source_array = null)
  {
      // do some folding here, for blocks
    if (is_string($value) && ((strpos($value, "\n") !== false || strpos($value, ': ') !== false || strpos($value, '- ') !== false ||
      strpos($value, '*') !== false || strpos($value, '#') !== false || strpos($value, '<') !== false || strpos($value, '>') !== false || strpos($value, '  ') !== false ||
      strpos($value, '[') !== false || strpos($value, ']') !== false || strpos($value, '{') !== false || strpos($value, '}') !== false) || strpos($value, '&') !== false || strpos($value, "'") !== false || strpos($value, '!') === 0 ||
      substr($value, -1, 1) == ':')
    ) {
        $value = $this->_doLiteralBlock($value, $indent);
    } else {
        $value  = $this->_doFolding($value, $indent);
    }
      if ($value === array()) {
          $value = '[ ]';
      }
      if ($value === '') {
          $value = '""';
      }
      if (self::isTranslationWord($value)) {
          $value = $this->_doLiteralBlock($value, $indent);
      }
      if (trim($value) != $value) {
          $value = $this->_doLiteralBlock($value, $indent);
      }
      if (is_bool($value)) {
          $value = $value ? 'true' : 'false';
      }
      if ($value === null) {
          $value = 'null';
      }
      if ($value === "'".self::REMPTY."'") {
          $value = null;
      }
      $spaces = str_repeat(' ', $indent);
    //if (is_int($key) && $key - 1 == $previous_key && $first_key===0) {
    if (is_array($source_array) && array_keys($source_array) === range(0, count($source_array) - 1)) {
        // It's a sequence
      $string = $spaces.'- '.$value."\n";
    } else {
        // if ($first_key===0)  throw new Exception('Keys are all screwy.  The first one was zero, now it\'s "'. $key .'"');
      // It's mapped
      if (strpos($key, ':') !== false || strpos($key, '#') !== false) {
          $key = '"'.$key.'"';
      }
        $string = rtrim($spaces.$key.': '.$value)."\n";
    }

      return $string;
  }
  /**
   * Creates a literal block for dumping.
   *
   * @access private
   *
   * @return string
   *
   * @param $value
   * @param $indent int The value of the indent
   */
  private function _doLiteralBlock($value, $indent)
  {
      if ($value === "\n") {
          return '\n';
      }
      if (strpos($value, "\n") === false && strpos($value, "'") === false) {
          return sprintf("'%s'", $value);
      }
      if (strpos($value, "\n") === false && strpos($value, '"') === false) {
          return sprintf('"%s"', $value);
      }
      $exploded = explode("\n", $value);
      $newValue = '|';
      $indent  += $this->_dumpIndent;
      $spaces   = str_repeat(' ', $indent);
      foreach ($exploded as $line) {
          $newValue .= "\n".$spaces.($line);
      }

      return $newValue;
  }
  /**
   * Folds a string of text, if necessary.
   *
   * @access private
   *
   * @return string
   *
   * @param $value The string you wish to fold
   */
  private function _doFolding($value, $indent)
  {
      // Don't do anything if wordwrap is set to 0
    if ($this->_dumpWordWrap !== 0 && is_string($value) && strlen($value) > $this->_dumpWordWrap) {
        $indent += $this->_dumpIndent;
        $indent = str_repeat(' ', $indent);
        $wrapped = wordwrap($value, $this->_dumpWordWrap, "\n$indent");
        $value   = ">\n".$indent.$wrapped;
    } else {
        if ($this->setting_dump_force_quotes && is_string($value) && $value !== self::REMPTY) {
            $value = '"'.$value.'"';
        }
        if (is_numeric($value) && is_string($value)) {
            $value = '"'.$value.'"';
        }
    }

      return $value;
  }
    private function isTrueWord($value)
    {
        $words = self::getTranslations(array('true', 'on', 'yes', 'y'));

        return in_array($value, $words, true);
    }
    private function isFalseWord($value)
    {
        $words = self::getTranslations(array('false', 'off', 'no', 'n'));

        return in_array($value, $words, true);
    }
    private function isNullWord($value)
    {
        $words = self::getTranslations(array('null', '~'));

        return in_array($value, $words, true);
    }
    private function isTranslationWord($value)
    {
        return (
      self::isTrueWord($value)  ||
      self::isFalseWord($value) ||
      self::isNullWord($value)
    );
    }
  /**
   * Coerce a string into a native type
   * Reference: http://yaml.org/type/bool.html
   * TODO: Use only words from the YAML spec.
   *
   * @access private
   *
   * @param $value The value to coerce
   */
  private function coerceValue(&$value)
  {
      if (self::isTrueWord($value)) {
          $value = true;
      } elseif (self::isFalseWord($value)) {
          $value = false;
      } elseif (self::isNullWord($value)) {
          $value = null;
      }
  }
  /**
   * Given a set of words, perform the appropriate translations on them to
   * match the YAML 1.1 specification for type coercing.
   *
   * @param $words The words to translate
   * @access private
   */
  private static function getTranslations(array $words)
  {
      $result = array();
      foreach ($words as $i) {
          $result = array_merge($result, array(ucfirst($i), strtoupper($i), strtolower($i)));
      }

      return $result;
  }
// LOADING FUNCTIONS
  private function __load($input)
  {
      $Source = $this->loadFromSource($input);

      return $this->loadWithSource($Source);
  }
    private function __loadString($input)
    {
        $Source = $this->loadFromString($input);

        return $this->loadWithSource($Source);
    }
    private function loadWithSource($Source)
    {
        if (empty($Source)) {
            return array();
        }
        if ($this->setting_use_syck_is_possible && function_exists('syck_load')) {
            $array = syck_load(implode("\n", $Source));

            return is_array($array) ? $array : array();
        }
        $this->path = array();
        $this->result = array();
        $cnt = count($Source);
        for ($i = 0; $i < $cnt; $i++) {
            $line = $Source[$i];
            $this->indent = strlen($line) - strlen(ltrim($line));
            $tempPath = $this->getParentPathByIndent($this->indent);
            $line = self::stripIndent($line, $this->indent);
            if (self::isComment($line)) {
                continue;
            }
            if (self::isEmpty($line)) {
                continue;
            }
            $this->path = $tempPath;
            $literalBlockStyle = self::startsLiteralBlock($line);
            if ($literalBlockStyle) {
                $line = rtrim($line, $literalBlockStyle." \n");
                $literalBlock = '';
                $line .= ' '.$this->LiteralPlaceHolder;
                $literal_block_indent = strlen($Source[$i+1]) - strlen(ltrim($Source[$i+1]));
                while (++$i < $cnt && $this->literalBlockContinues($Source[$i], $this->indent)) {
                    $literalBlock = $this->addLiteralLine($literalBlock, $Source[$i], $literalBlockStyle, $literal_block_indent);
                }
                $i--;
            }
      // Strip out comments
      if (strpos($line, '#')) {
          $line = preg_replace('/\s*#([^"\']+)$/', '', $line);
      }
            while (++$i < $cnt && self::greedilyNeedNextLine($line)) {
                $line = rtrim($line, " \n\t\r").' '.ltrim($Source[$i], " \t");
            }
            $i--;
            $lineArray = $this->_parseLine($line);
            if ($literalBlockStyle) {
                $lineArray = $this->revertLiteralPlaceHolder($lineArray, $literalBlock);
            }
            $this->addArray($lineArray, $this->indent);
            foreach ($this->delayedPath as $indent => $delayedPath) {
                $this->path[$indent] = $delayedPath;
            }
            $this->delayedPath = array();
        }

        return $this->result;
    }
    private function loadFromSource($input)
    {
        if (!empty($input) && strpos($input, "\n") === false && file_exists($input)) {
            $input = file_get_contents($input);
        }

        return $this->loadFromString($input);
    }
    private function loadFromString($input)
    {
        $lines = explode("\n", $input);
        foreach ($lines as $k => $_) {
            $lines[$k] = rtrim($_, "\r");
        }

        return $lines;
    }
  /**
   * Parses YAML code and returns an array for a node.
   *
   * @access private
   *
   * @return array
   *
   * @param string $line A line from the YAML file
   */
  private function _parseLine($line)
  {
      if (!$line) {
          return array();
      }
      $line = trim($line);
      if (!$line) {
          return array();
      }
      $array = array();
      $group = $this->nodeContainsGroup($line);
      if ($group) {
          $this->addGroup($line, $group);
          $line = $this->stripGroup($line, $group);
      }
      if ($this->startsMappedSequence($line)) {
          return $this->returnMappedSequence($line);
      }
      if ($this->startsMappedValue($line)) {
          return $this->returnMappedValue($line);
      }
      if ($this->isArrayElement($line)) {
          return $this->returnArrayElement($line);
      }
      if ($this->isPlainArray($line)) {
          return $this->returnPlainArray($line);
      }

      return $this->returnKeyValuePair($line);
  }
  /**
   * Finds the type of the passed value, returns the value as the new type.
   *
   * @access private
   *
   * @param string $value
   *
   * @return mixed
   */
  private function _toType($value)
  {
      if ($value === '') {
          return '';
      }
      $first_character = $value[0];
      $last_character = substr($value, -1, 1);
      $is_quoted = false;
      do {
          if (!$value) {
              break;
          }
          if ($first_character != '"' && $first_character != "'") {
              break;
          }
          if ($last_character != '"' && $last_character != "'") {
              break;
          }
          $is_quoted = true;
      } while (0);
      if ($is_quoted) {
          $value = str_replace('\n', "\n", $value);

          return strtr(substr($value, 1, -1), array('\\"' => '"', '\'\'' => '\'', '\\\'' => '\''));
      }
      if (strpos($value, ' #') !== false && !$is_quoted) {
          $value = preg_replace('/\s+#(.+)$/', '', $value);
      }
      if ($first_character == '[' && $last_character == ']') {
          // Take out strings sequences and mappings
      $innerValue = trim(substr($value, 1, -1));
          if ($innerValue === '') {
              return array();
          }
          $explode = $this->_inlineEscape($innerValue);
      // Propagate value array
      $value  = array();
          foreach ($explode as $v) {
              $value[] = $this->_toType($v);
          }

          return $value;
      }
      if (strpos($value, ': ') !== false && $first_character != '{') {
          $array = explode(': ', $value);
          $key   = trim($array[0]);
          array_shift($array);
          $value = trim(implode(': ', $array));
          $value = $this->_toType($value);

          return array($key => $value);
      }
      if ($first_character == '{' && $last_character == '}') {
          $innerValue = trim(substr($value, 1, -1));
          if ($innerValue === '') {
              return array();
          }
      // Inline Mapping
      // Take out strings sequences and mappings
      $explode = $this->_inlineEscape($innerValue);
      // Propagate value array
      $array = array();
          foreach ($explode as $v) {
              $SubArr = $this->_toType($v);
              if (empty($SubArr)) {
                  continue;
              }
              if (is_array($SubArr)) {
                  $array[key($SubArr)] = $SubArr[key($SubArr)];
                  continue;
              }
              $array[] = $SubArr;
          }

          return $array;
      }
      if ($value == 'null' || $value == 'NULL' || $value == 'Null' || $value == '' || $value == '~') {
          return;
      }
      if (is_numeric($value) && preg_match('/^(-|)[1-9]+[0-9]*$/', $value)) {
          $intvalue = (int) $value;
          if ($intvalue != PHP_INT_MAX) {
              $value = $intvalue;
          }

          return $value;
      }
      if (is_numeric($value) && preg_match('/^0[xX][0-9a-fA-F]+$/', $value)) {
          // Hexadecimal value.
      return hexdec($value);
      }
      $this->coerceValue($value);
      if (is_numeric($value)) {
          if ($value === '0') {
              return 0;
          }
          if (rtrim($value, 0) === $value) {
              $value = (float) $value;
          }

          return $value;
      }

      return $value;
  }
  /**
   * Used in inlines to check for more inlines or quoted strings.
   *
   * @access private
   *
   * @return array
   */
  private function _inlineEscape($inline)
  {
      // There's gotta be a cleaner way to do this...
    // While pure sequences seem to be nesting just fine,
    // pure mappings and mappings with sequences inside can't go very
    // deep.  This needs to be fixed.
    $seqs = array();
      $maps = array();
      $saved_strings = array();
      $saved_empties = array();
    // Check for empty strings
    $regex = '/("")|(\'\')/';
      if (preg_match_all($regex, $inline, $strings)) {
          $saved_empties = $strings[0];
          $inline  = preg_replace($regex, 'YAMLEmpty', $inline);
      }
      unset($regex);
    // Check for strings
    $regex = '/(?:(")|(?:\'))((?(1)[^"]+|[^\']+))(?(1)"|\')/';
      if (preg_match_all($regex, $inline, $strings)) {
          $saved_strings = $strings[0];
          $inline  = preg_replace($regex, 'YAMLString', $inline);
      }
      unset($regex);
    // echo $inline;
    $i = 0;
      do {
          // Check for sequences
    while (preg_match('/\[([^{}\[\]]+)\]/U', $inline, $matchseqs)) {
        $seqs[] = $matchseqs[0];
        $inline = preg_replace('/\[([^{}\[\]]+)\]/U', ('YAMLSeq'.(count($seqs) - 1).'s'), $inline, 1);
    }
    // Check for mappings
    while (preg_match('/{([^\[\]{}]+)}/U', $inline, $matchmaps)) {
        $maps[] = $matchmaps[0];
        $inline = preg_replace('/{([^\[\]{}]+)}/U', ('YAMLMap'.(count($maps) - 1).'s'), $inline, 1);
    }
          if ($i++ >= 10) {
              break;
          }
      } while (strpos($inline, '[') !== false || strpos($inline, '{') !== false);
      $explode = explode(',', $inline);
      $explode = array_map('trim', $explode);
      $stringi = 0;
      $i = 0;
      while (1) {
          // Re-add the sequences
    if (!empty($seqs)) {
        foreach ($explode as $key => $value) {
            if (strpos($value, 'YAMLSeq') !== false) {
                foreach ($seqs as $seqk => $seq) {
                    $explode[$key] = str_replace(('YAMLSeq'.$seqk.'s'), $seq, $value);
                    $value = $explode[$key];
                }
            }
        }
    }
    // Re-add the mappings
    if (!empty($maps)) {
        foreach ($explode as $key => $value) {
            if (strpos($value, 'YAMLMap') !== false) {
                foreach ($maps as $mapk => $map) {
                    $explode[$key] = str_replace(('YAMLMap'.$mapk.'s'), $map, $value);
                    $value = $explode[$key];
                }
            }
        }
    }
    // Re-add the strings
    if (!empty($saved_strings)) {
        foreach ($explode as $key => $value) {
            while (strpos($value, 'YAMLString') !== false) {
                $explode[$key] = preg_replace('/YAMLString/', $saved_strings[$stringi], $value, 1);
                unset($saved_strings[$stringi]);
                ++$stringi;
                $value = $explode[$key];
            }
        }
    }
    // Re-add the empties
    if (!empty($saved_empties)) {
        foreach ($explode as $key => $value) {
            while (strpos($value, 'YAMLEmpty') !== false) {
                $explode[$key] = preg_replace('/YAMLEmpty/', '', $value, 1);
                $value = $explode[$key];
            }
        }
    }
          $finished = true;
          foreach ($explode as $key => $value) {
              if (strpos($value, 'YAMLSeq') !== false) {
                  $finished = false;
                  break;
              }
              if (strpos($value, 'YAMLMap') !== false) {
                  $finished = false;
                  break;
              }
              if (strpos($value, 'YAMLString') !== false) {
                  $finished = false;
                  break;
              }
              if (strpos($value, 'YAMLEmpty') !== false) {
                  $finished = false;
                  break;
              }
          }
          if ($finished) {
              break;
          }
          $i++;
          if ($i > 10) {
              break;
          } // Prevent infinite loops.
      }

      return $explode;
  }
    private function literalBlockContinues($line, $lineIndent)
    {
        if (!trim($line)) {
            return true;
        }
        if (strlen($line) - strlen(ltrim($line)) > $lineIndent) {
            return true;
        }

        return false;
    }
    private function referenceContentsByAlias($alias)
    {
        do {
            if (!isset($this->SavedGroups[$alias])) {
                echo "Bad group name: $alias.";
                break;
            }
            $groupPath = $this->SavedGroups[$alias];
            $value = $this->result;
            foreach ($groupPath as $k) {
                $value = $value[$k];
            }
        } while (false);

        return $value;
    }
    private function addArrayInline($array, $indent)
    {
        $CommonGroupPath = $this->path;
        if (empty($array)) {
            return false;
        }
        foreach ($array as $k => $_) {
            $this->addArray(array($k => $_), $indent);
            $this->path = $CommonGroupPath;
        }

        return true;
    }
    private function addArray($incoming_data, $incoming_indent)
    {
        // print_r ($incoming_data);
    if (count($incoming_data) > 1) {
        return $this->addArrayInline($incoming_data, $incoming_indent);
    }
        $key = key($incoming_data);
        $value = isset($incoming_data[$key]) ? $incoming_data[$key] : null;
        if ($key === '__!YAMLZero') {
            $key = '0';
        }
        if ($incoming_indent == 0 && !$this->_containsGroupAlias && !$this->_containsGroupAnchor) { // Shortcut for root-level values.
      if ($key || $key === '' || $key === '0') {
          $this->result[$key] = $value;
      } else {
          $this->result[] = $value;
          end($this->result);
          $key = key($this->result);
      }
            $this->path[$incoming_indent] = $key;

            return;
        }
        $history = array();
    // Unfolding inner array tree.
    $history[] = $_arr = $this->result;
        foreach ($this->path as $k) {
            $history[] = $_arr = $_arr[$k];
        }
        if ($this->_containsGroupAlias) {
            $value = $this->referenceContentsByAlias($this->_containsGroupAlias);
            $this->_containsGroupAlias = false;
        }
    // Adding string or numeric key to the innermost level or $this->arr.
    if (is_string($key) && $key == '<<') {
        if (!is_array($_arr)) {
            $_arr = array();
        }
        $_arr = array_merge($_arr, $value);
    } elseif ($key || $key === '' || $key === '0') {
        if (!is_array($_arr)) {
            $_arr = array($key => $value);
        } else {
            $_arr[$key] = $value;
        }
    } else {
        if (!is_array($_arr)) {
            $_arr = array($value);
            $key = 0;
        } else {
            $_arr[] = $value;
            end($_arr);
            $key = key($_arr);
        }
    }
        $reverse_path = array_reverse($this->path);
        $reverse_history = array_reverse($history);
        $reverse_history[0] = $_arr;
        $cnt = count($reverse_history) - 1;
        for ($i = 0; $i < $cnt; $i++) {
            $reverse_history[$i+1][$reverse_path[$i]] = $reverse_history[$i];
        }
        $this->result = $reverse_history[$cnt];
        $this->path[$incoming_indent] = $key;
        if ($this->_containsGroupAnchor) {
            $this->SavedGroups[$this->_containsGroupAnchor] = $this->path;
            if (is_array($value)) {
                $k = key($value);
                if (!is_int($k)) {
                    $this->SavedGroups[$this->_containsGroupAnchor][$incoming_indent + 2] = $k;
                }
            }
            $this->_containsGroupAnchor = false;
        }
    }
    private static function startsLiteralBlock($line)
    {
        $lastChar = substr(trim($line), -1);
        if ($lastChar != '>' && $lastChar != '|') {
            return false;
        }
        if ($lastChar == '|') {
            return $lastChar;
        }
    // HTML tags should not be counted as literal blocks.
    if (preg_match('#<.*?>$#', $line)) {
        return false;
    }

        return $lastChar;
    }
    private static function greedilyNeedNextLine($line)
    {
        $line = trim($line);
        if (!strlen($line)) {
            return false;
        }
        if (substr($line, -1, 1) == ']') {
            return false;
        }
        if ($line[0] == '[') {
            return true;
        }
        if (preg_match('#^[^:]+?:\s*\[#', $line)) {
            return true;
        }

        return false;
    }
    private function addLiteralLine($literalBlock, $line, $literalBlockStyle, $indent = -1)
    {
        $line = self::stripIndent($line, $indent);
        if ($literalBlockStyle !== '|') {
            $line = self::stripIndent($line);
        }
        $line = rtrim($line, "\r\n\t ")."\n";
        if ($literalBlockStyle == '|') {
            return $literalBlock.$line;
        }
        if (strlen($line) == 0) {
            return rtrim($literalBlock, ' ')."\n";
        }
        if ($line == "\n" && $literalBlockStyle == '>') {
            return rtrim($literalBlock, " \t")."\n";
        }
        if ($line != "\n") {
            $line = trim($line, "\r\n ").' ';
        }

        return $literalBlock.$line;
    }
    public function revertLiteralPlaceHolder($lineArray, $literalBlock)
    {
        foreach ($lineArray as $k => $_) {
            if (is_array($_)) {
                $lineArray[$k] = $this->revertLiteralPlaceHolder($_, $literalBlock);
            } elseif (substr($_, -1 * strlen($this->LiteralPlaceHolder)) == $this->LiteralPlaceHolder) {
                $lineArray[$k] = rtrim($literalBlock, " \r\n");
            }
        }

        return $lineArray;
    }
    private static function stripIndent($line, $indent = -1)
    {
        if ($indent == -1) {
            $indent = strlen($line) - strlen(ltrim($line));
        }

        return substr($line, $indent);
    }
    private function getParentPathByIndent($indent)
    {
        if ($indent == 0) {
            return array();
        }
        $linePath = $this->path;
        do {
            end($linePath);
            $lastIndentInParentPath = key($linePath);
            if ($indent <= $lastIndentInParentPath) {
                array_pop($linePath);
            }
        } while ($indent <= $lastIndentInParentPath);

        return $linePath;
    }
    private function clearBiggerPathValues($indent)
    {
        if ($indent == 0) {
            $this->path = array();
        }
        if (empty($this->path)) {
            return true;
        }
        foreach ($this->path as $k => $_) {
            if ($k > $indent) {
                unset($this->path[$k]);
            }
        }

        return true;
    }
    private static function isComment($line)
    {
        if (!$line) {
            return false;
        }
        if ($line[0] == '#') {
            return true;
        }
        if (trim($line, " \r\n\t") == '---') {
            return true;
        }

        return false;
    }
    private static function isEmpty($line)
    {
        return (trim($line) === '');
    }
    private function isArrayElement($line)
    {
        if (!$line || !is_scalar($line)) {
            return false;
        }
        if (substr($line, 0, 2) != '- ') {
            return false;
        }
        if (strlen($line) > 3) {
            if (substr($line, 0, 3) == '---') {
                return false;
            }
        }

        return true;
    }
    private function isHashElement($line)
    {
        return strpos($line, ':');
    }
    private function isLiteral($line)
    {
        if ($this->isArrayElement($line)) {
            return false;
        }
        if ($this->isHashElement($line)) {
            return false;
        }

        return true;
    }
    private static function unquote($value)
    {
        if (!$value) {
            return $value;
        }
        if (!is_string($value)) {
            return $value;
        }
        if ($value[0] == '\'') {
            return trim($value, '\'');
        }
        if ($value[0] == '"') {
            return trim($value, '"');
        }

        return $value;
    }
    private function startsMappedSequence($line)
    {
        return (substr($line, 0, 2) == '- ' && substr($line, -1, 1) == ':');
    }
    private function returnMappedSequence($line)
    {
        $array = array();
        $key         = self::unquote(trim(substr($line, 1, -1)));
        $array[$key] = array();
        $this->delayedPath = array(strpos($line, $key) + $this->indent => $key);

        return array($array);
    }
    private function checkKeysInValue($value)
    {
        if (strchr('[{"\'', $value[0]) === false) {
            if (strchr($value, ': ') !== false) {
                throw new Exception('Too many keys: '.$value);
            }
        }
    }
    private function returnMappedValue($line)
    {
        $this->checkKeysInValue($line);
        $array = array();
        $key         = self::unquote(trim(substr($line, 0, -1)));
        $array[$key] = '';

        return $array;
    }
    private function startsMappedValue($line)
    {
        return (substr($line, -1, 1) == ':');
    }
    private function isPlainArray($line)
    {
        return ($line[0] == '[' && substr($line, -1, 1) == ']');
    }
    private function returnPlainArray($line)
    {
        return $this->_toType($line);
    }
    private function returnKeyValuePair($line)
    {
        $array = array();
        $key = '';
        if (strpos($line, ': ')) {
            // It's a key/value pair most likely
      // If the key is in double quotes pull it out
      if (($line[0] == '"' || $line[0] == "'") && preg_match('/^(["\'](.*)["\'](\s)*:)/', $line, $matches)) {
          $value = trim(str_replace($matches[1], '', $line));
          $key   = $matches[2];
      } else {
          // Do some guesswork as to the key and the value
        $explode = explode(': ', $line);
          $key     = trim(array_shift($explode));
          $value   = trim(implode(': ', $explode));
          $this->checkKeysInValue($value);
      }
      // Set the type of the value.  Int, string, etc
      $value = $this->_toType($value);
            if ($key === '0') {
                $key = '__!YAMLZero';
            }
            $array[$key] = $value;
        } else {
            $array = array($line);
        }

        return $array;
    }
    private function returnArrayElement($line)
    {
        if (strlen($line) <= 1) {
            return array(array());
        } // Weird %)
     $array = array();
        $value   = trim(substr($line, 1));
        $value   = $this->_toType($value);
        if ($this->isArrayElement($value)) {
            $value = $this->returnArrayElement($value);
        }
        $array[] = $value;

        return $array;
    }
    private function nodeContainsGroup($line)
    {
        $symbolsForReference = 'A-z0-9_\-';
        if (strpos($line, '&') === false && strpos($line, '*') === false) {
            return false;
        } // Please die fast ;-)
    if ($line[0] == '&' && preg_match('/^(&['.$symbolsForReference.']+)/', $line, $matches)) {
        return $matches[1];
    }
        if ($line[0] == '*' && preg_match('/^(\*['.$symbolsForReference.']+)/', $line, $matches)) {
            return $matches[1];
        }
        if (preg_match('/(&['.$symbolsForReference.']+)$/', $line, $matches)) {
            return $matches[1];
        }
        if (preg_match('/(\*['.$symbolsForReference.']+$)/', $line, $matches)) {
            return $matches[1];
        }
        if (preg_match('#^\s*<<\s*:\s*(\*[^\s]+).*$#', $line, $matches)) {
            return $matches[1];
        }

        return false;
    }
    private function addGroup($line, $group)
    {
        if ($group[0] == '&') {
            $this->_containsGroupAnchor = substr($group, 1);
        }
        if ($group[0] == '*') {
            $this->_containsGroupAlias = substr($group, 1);
        }
    //print_r ($this->path);
    }
    private function stripGroup($line, $group)
    {
        $line = trim(str_replace($group, '', $line));

        return $line;
    }
}
// Enable use of Spyc from command line
// The syntax is the following: php Spyc.php spyc.yaml
do {
    if (PHP_SAPI != 'cli') {
        break;
    }
    if (empty($_SERVER['argc']) || $_SERVER['argc'] < 2) {
        break;
    }
    if (empty($_SERVER['PHP_SELF']) || false === strpos($_SERVER['PHP_SELF'], 'Spyc.php')) {
        break;
    }
    $file = $argv[1];
    echo json_encode(spyc_load_file($file));
} while (0);


// Source: classes/export/YAMLExport.php



class YAMLExport implements ExportInterface
{
    public function export($arr, $directory, $language)
    {
        $file = $directory . DIRECTORY_SEPARATOR . $language . '.yml';

        $fp = fopen($file, 'w');
        fwrite($fp, spyc_dump($arr));
        fclose($fp);

        return file_exists($file);
    }
}


// Source: classes/general/Foler.php


/**
 * @author Serkin Alexander <serkin.alexander@gmail.com>
 */
class Foler
{
    /**
     * @var PDO
     */
    private $dbh = null;

    /**
     * @var string
     */
    private $dbDSN;

    /**
     * @var string
     */
    private $dbUser;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $dbPassword;


    /**
     * @param string $dbDSN
     * @param string $dbUser
     * @param string $dbPassword
     */
    public function __construct($dbDSN, $dbUser, $dbPassword = '')
    {
        $this->dbDSN        = $dbDSN;
        $this->dbUser       = $dbUser;
        $this->dbPassword   = $dbPassword;
    }

    public function hasError()
    {
        return !empty($this->error);
    }

    public function getError()
    {
        return $this->error;
    }

    public function clearError()
    {
        $this->error = null;
    }

    /**
     * @param string $error
     */
    public function setError($error) {
        $this->error = $error;
    }

    /**
     * Connects to database.
     *
     * @throws PDOException
     */
    public function connect()
    {
        $dbh = new PDO($this->dbDSN, $this->dbUser, $this->dbPassword);
        $this->dbh = $dbh;
        $this->dbh->exec('SET NAMES utf8');

    }

    /**
     * Gets all projects.
     *
     * @return array
     */
    public function getAllProjects()
    {
        $sth = $this->dbh->prepare('SELECT * FROM `project`');
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all codes from project.
     *
     * @param int    $idProject
     * @param string $keyword
     *
     * @return array
     */
    public function getAllCodes($idProject, $keyword = null)
    {
        $sth = $this->dbh->prepare('SELECT DISTINCT (`code`) FROM `translation` WHERE `id_project` = ? and `code` like ?');

        $keyword = !is_null($keyword) ? "%$keyword%" : '%%';

        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->bindParam(2, $keyword, PDO::PARAM_STR);

        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets translation according with given code and idProject or all records.
     *
     * @param int    $idProject
     * @param string $code
     *
     * @return array
     */
    public function getTranslation($idProject, $code = null)
    {
        $languages = $this->getLanguagesFromProject($idProject);

        $dbRecords = !is_null($code) ? $this->getCodeTranslation($idProject, $code) : array();

        $returnValue = array();
        $returnValue['code'] = $code;

        foreach ($languages as $lang):
            $returnValue['translations'][] = [
                'language'      => $lang,
                'translation'   => !empty($dbRecords[$lang]) ? $dbRecords[$lang] : '',
            ];
        endforeach;

        return $returnValue;
    }

    public function getAllTranslationsFromProject($idProject)
    {
        $sth = $this->dbh->prepare('SELECT * FROM `translation` WHERE `id_project` = ?');
        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCodeTranslation($idProject, $code)
    {
        $returnValue = array();

        $sth = $this->dbh->prepare('SELECT * FROM `translation` WHERE `id_project` = ? and `code` = ?');
        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->bindParam(2, $code, PDO::PARAM_STR);

        $sth->execute();

        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $record):
            $returnValue[$record['language']] = $record['translation'];
        endforeach;

        return $returnValue;
    }

    /**
     * Get all information about project.
     *
     * @param int $idProject
     *
     * @return array
     */
    public function getProjectById($idProject)
    {
        $sth = $this->dbh->prepare('SELECT * FROM `project` WHERE `id_project` = ?');
        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Saves project.
     *
     * Edits project if $idProject was specified
     *
     * @param string $name
     * @param string $path
     * @param string $languages
     * @param int    $idProject
     *
     * @return int|bool False on error
     */
    public function saveProject($name, $path, $languages, $idProject = null)
    {

        $this->clearError();

        if (is_null($idProject)) {
            $sth = $this->dbh->prepare('INSERT INTO `project` (`name`, `path`, `languages`) VALUES(?, ?, ?)');
        } else {
            $sth = $this->dbh->prepare('UPDATE `project` SET `name` = ?, `path` = ?, `languages` = ? WHERE `id_project` = ?');
        }

        $sth->bindParam(1, $name, PDO::PARAM_STR);
        $sth->bindParam(2, $path, PDO::PARAM_STR);
        $sth->bindParam(3, $languages, PDO::PARAM_STR);

        if (is_null($idProject)) {
            $sth->execute();
            $returnValue = $this->dbh->lastInsertId() ? $this->dbh->lastInsertId() : 0;
        } else {
            $sth->bindParam(4, $idProject, PDO::PARAM_INT);
            $sth->execute();
            $returnValue = $idProject;
        }


        if(!empty($sth->errorInfo()[2])) {
            $this->setError($sth->errorInfo()[2]);
        }

        return $returnValue;
    }

    /**
     * Removes project.
     *
     * @param int $idProject
     *
     * @return bool
     */
    public function deleteProject($idProject)
    {
        $sth = $this->dbh->prepare('DELETE FROM `project` WHERE `id_project` = ?');

        $sth->bindParam(1, $idProject, PDO::PARAM_INT);

        return $sth->execute();
    }

    /**
     * @param int    $idProject
     * @param string $code
     * @param array  $arr
     *
     * @return bool
     */
    public function saveTranslation($idProject, $code, $arr)
    {
        $languages = $this->getLanguagesFromProject($idProject);

        foreach ($languages as $language) {

            if (isset($arr[$language])) {
                $value = !empty($arr[$language]) ? $arr[$language] : '';

                $sth = $this->dbh->prepare('INSERT INTO `translation` (`id_project`, `code`, `language`, `translation`) VALUES(?, ?, ?, ?)'
                                .'ON DUPLICATE KEY UPDATE `translation` = ?');

                $sth->bindParam(1, $idProject, PDO::PARAM_INT);
                $sth->bindParam(2, $code, PDO::PARAM_STR);
                $sth->bindParam(3, $language, PDO::PARAM_STR);
                $sth->bindParam(4, $value, PDO::PARAM_STR);
                $sth->bindParam(5, $value, PDO::PARAM_STR);

                if ($sth->execute() === false) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param int    $idProject
     * @param string $code
     *
     * @return bool
     */
    public function deleteCode($idProject, $code)
    {
        $sth = $this->dbh->prepare('DELETE FROM `translation` WHERE `code` = ? and `id_project` = ?');

        $sth->bindParam(1, $code, PDO::PARAM_STR);
        $sth->bindParam(2, $idProject, PDO::PARAM_INT);

        return $sth->execute();
    }

    public function getLanguagesFromProject($idProject)
    {
        $returnValue = array();

        $data = $this->getProjectById($idProject);

        if (!empty($data['languages'])):
            $returnValue = explode(',', $data['languages']);
        endif;

        return $returnValue;
    }
}


// Source: classes/general/Response.php


/**
 * Class creates two types of response according having error.
 *
 * @author Serkin Alexander <serkin.alexander@gmail.com>
 */
class Response {

    public static function sendResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public static function responseWithError($message) {

        $response = array(
            'status' => array(
                'state' => 'notOk',
                'message' => $message,
            ),
            'data' => array(),
        );

        self::sendResponse($response);
    }

    public static function responseWithSuccess($arr, $statusMessage = '') {

        $response = array(
            'status' => array(
                'state' => 'Ok',
                'message' => $statusMessage,
            ),
            'data' => $arr,
        );

        self::sendResponse($response);
    }

}


// Source: i18n/en.php



$app['i18n']['en'] = array(
    'layout' => array(
        'code_placeholder'  => 'code',
        'save'              => 'Save',
        'code'              => 'Code',
        'title'             => 'Foler - single page translation system',
        'name'              => 'Name',
        'manage'            => 'Manage',
        'languages'         => 'Languages',
        'path'              => 'Path for export',
        'clear'             => 'Clear',
        'new_translation'   => 'Add new translation',
        'add_project'       => 'Add/edit project',
        'export'       => 'Export',
        'delete'            => 'delete'
        ),
    'foler' => array(
        'project_saved' => 'Project saved!',
        'project_removed' => 'Project removed!',
        'translation_saved' => 'Translation saved!',
        'code_removed' => 'Code removed!',
        'project_exported' => 'Project exported!',
    ),
    'errors' => array(
        'empty_code' => 'Code not specified',
        'cannot_export_project' => 'Cannot export  project',
        'project_path_not_writable' => 'Cannot write to project export path',
        'empty_id_project' => 'ID project not specified',
        'empty_project_name' => 'Project name not specified',
        'empty_project_export_path' => 'Project export path not specified',
        'not_valid_project_languages' => 'Languages field should be unique two letters string separated by comma',
        'not_valid_project_code' => 'Code field should consists of only dots, numbers and letters in loser case'
    )
);


// Source: controllers/code/delete.php


$app['controllers']['code/delete'] = function($app, $request) {

    $idProject  = !empty($request['id_project']) ? (int)$request['id_project'] : null;
    $code       = !empty($request['code']) ? $request['code'] : null;

    if (empty($idProject)) {
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_id_project'];
    } elseif (empty($code)) {
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_code'];
    } else {
        $result     = $app['foler']->deleteCode($idProject, $code);
        $errorMsg   = $app['foler']->getError();
    }

    if ($result) {
        Response::responseWithSuccess(array(), $app['i18n']['foler']['code_removed']);
    } else {
        Response::responseWithError($errorMsg);
    }

};


// Source: controllers/code/search.php



$app['controllers']['code/search'] = function($app, $request) {

    $keyword    = !empty($request['keyword']) ? $request['keyword'] : null;
    $idProject  = !empty($request['id_project']) ? (int)$request['id_project'] : null;

    $codes = $app['foler']->getAllCodes($idProject, $keyword);
    Response::responseWithSuccess(array('codes' => $codes));

};


// Source: controllers/project/delete.php



$app['controllers']['project/delete'] = function($app, $request) {

    $idProject = !empty($request['id_project']) ? (int)$request['id_project'] : null;

    if (empty($idProject)) {
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_id_project'];
    } else {
        $result     = $app['foler']->deleteProject($idProject);
        $errorMsg   = $app['foler']->getError();
    }

    if ($result) {
        Response::responseWithSuccess(array(), $app['i18n']['foler']['project_removed']);
    } else {
        Response::responseWithError($errorMsg);
    }

};


// Source: controllers/project/export.php


$joinStringToArr = function($string, $value, &$arr = []) {

    $keys = explode('.', $string);

    $ref = &$arr;

    while ($key = array_shift($keys)) {
        $ref = &$ref[$key];
    }

    $ref = $value;

};

$app['controllers']['project/export'] = function($app, $request) use ($joinStringToArr) {

    $idProject  = !empty($request['id_project']) ? (int)$request['id_project'] : null;
    $type       = (!empty($request['type']) && in_array($request['type'], array('php', 'yaml'))) ? $request['type'] : 'php';

    $project    = $app['foler']->getProjectById($idProject);
    $languages  = $app['foler']->getLanguagesFromProject($idProject);

    $result = true;
    $directory = $project['path'];

    if (empty($project['path']) || !is_writable($directory)) {
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['project_path_not_writable'] . ': ' . $directory;

    } else {

        switch ($type) {
            case 'php':
                $export = new PHPExport();
                break;

            case 'yaml':
                $export = new YAMLExport();
                break;
        }

        $records = $app['foler']->getAllTranslationsFromProject($idProject);

        foreach ($languages as $language):
            $out = array();

            foreach ($records as $record):
                if ($record['language'] == $language):
                    $joinStringToArr($record['code'], $record['translation'], $out);
                endif;
            endforeach;

            if ($export->export($out, $directory, $language) === false):
                $result     = false;
                $errorMsg   = $app['i18n']['errors']['cannot_export_project'] . ': ' . $language;
            endif;

        endforeach;

}

    if ($result === true) {
        Response::responseWithSuccess(array(), $app['i18n']['foler']['project_exported']);
    } else {
        Response::responseWithError($errorMsg);
    }

};


// Source: controllers/project/getall.php


$app['controllers']['project/getall'] = function($app) {

    $projects = $app['foler']->getAllProjects();
    Response::responseWithSuccess(array('projects' => $projects));

};


// Source: controllers/project/getone.php


$app['controllers']['project/getone'] = function($app, $request) {

    $idProject = !empty($request['id_project']) ? (int) $request['id_project'] : null;

    if (!is_null($idProject)) {
        $project = $app['foler']->getProjectByID($idProject);
        Response::responseWithSuccess(array('project' => $project));
    } else {
        Response::responseWithError($app['i18n']['errors']['empty_id_project']);
    }
};


// Source: controllers/project/save.php


$isLanguagesValid =  function($languages) {

    $returnValue = true;

    if (strpos($languages, ' ') !== false):
        $returnValue = false;
    endif;

    $uniqueArr = array();

    foreach (explode(',', $languages) as $value):
        if (empty($value) || strlen($value) != 2 || isset($uniqueArr[$value])):
            $returnValue = false;
        endif;
        $uniqueArr[$value] = 1;
    endforeach;

    return $returnValue;

};

$app['controllers']['project/save'] = function ($app, $request) use ($isLanguagesValid) {

    parse_str($request['form'], $form);

    $idProject = !empty($form['id_project']) ? $form['id_project'] : null;

    if (empty($form['languages']) || $isLanguagesValid($form['languages']) === false) {
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['not_valid_project_languages'];
    } elseif (empty($form['path'])) {
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_project_export_path'];
    } elseif (empty($form['name'])) {
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_project_name'];
    } else {
        $result     = $app['foler']->saveProject($form['name'], $form['path'], $form['languages'], $idProject);
        $errorMsg   = $app['foler']->getError();
    }

    if ($result) {
        Response::responseWithSuccess(array('id_project' => $result), $app['i18n']['foler']['project_saved']);
    } else {
        Response::responseWithError($errorMsg);
    }

};


// Source: controllers/transaltion/getone.php


$app['controllers']['translation/getone'] = function($app, $request) {

    $code = !empty($request['code']) ? $request['code'] : null;
    $idProject = !empty($request['id_project']) ? (int) $request['id_project'] : null;

    $result = $app['foler']->getTranslation($idProject, $code);
    Response::responseWithSuccess($result);
};


// Source: controllers/transaltion/save.php


$isCodeValid = function ($code) {

    return preg_match('/^[a-z0-9_\.]+$/', $code) === 1 ? true : false;

};

$app['controllers']['translation/save'] = function ($app, $request) use ($isCodeValid) {

    parse_str(urldecode($request['form']), $form);

    $idProject  = !empty($form['id_project'])   ? $form['id_project']   : null;
    $code       = !empty($form['code'])         ? $form['code']         : null;
    $arr        = !empty($form['translation'])  ? $form['translation']  : array();

    if (empty($idProject)):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_id_project'];
    elseif (empty($code) || $isCodeValid($code) === false):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['not_valid_project_code'];
    else:
        $result     = $app['foler']->saveTranslation($idProject, $code, $arr);
        $errorMsg   = $app['foler']->getError();
    endif;

    if ($result):
        Response::responseWithSuccess(array(), $app['i18n']['foler']['translation_saved']);
    else:
        Response::responseWithError($errorMsg);
    endif;

};


// Source: config/footer.php



$app['foler'] = new Foler($app['config']['db']['dsn'], $app['config']['db']['user'], $app['config']['db']['password'], $app['i18n']);

try {
    $app['foler']->connect();
} catch (Exception $exc){
    Response::responseWithError($exc->getMessage());
}

$i18n = $app['i18n'] = $app['i18n'][$app['locale']];


if(!empty($_REQUEST['action']) && isset($app['controllers'][$_REQUEST['action']])):
    $app['controllers'][$_REQUEST['action']]($app, $_REQUEST);
    die();
endif;

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $i18n['layout']['title']; ?></title>

        <style>
            html {
                margin: 0;
                padding: 0;
            }
            table {
                border: #cecece 1px solid;
            }

            .selected_project {
                background-color: #cecece;
            }
            #status_field {
                height: 30px;
                padding: 5px;
                margin: 5px;
            }
            #status_field p {
                padding: 10px;
                font-size: 14pt;
            }
            #codesBlock {
                margin-top: 10px;
            }
            #newTranslationButton {
                margin-bottom: 10px;
            }
            #searchKeyword {
                display: none;
            }
        </style>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/0.8.1/mustache.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    </head>
    <body onLoad="projects.reload()">
        <input type="hidden" id="idGlobalProject" value="">

        <div class="row">
            <div class="col-md-8">
                <h4><?php echo $i18n['layout']['title']; ?></h4>
            </div>
            <div class="col-md-3">
                <div id="status_field">&nbsp;</div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <div id="projectsBlock"></div>

                <script id="projectsTemplate" type="x-tmpl-mustache">
                    <table class="table table-condensed">
                        <thead>
                            <th>#</th>
                            <th><?php echo $i18n['layout']['name']; ?></th>
                            <th><?php echo $i18n['layout']['export']; ?></th>
                            <th><?php echo $i18n['layout']['manage']; ?></th>
                        </thead>
                        <tbody>
                            {{#projects}}
                                <tr OnClick="projects.selectProjectById({{id_project}})" class="project_block" id="project_block_{{id_project}}">
                                    <td>{{id_project}}</td>
                                    <td>{{name}}</td>
                                    <td>
                                        <button class="btn btn-info btn-xs" OnClick="projects.export({{id_project}},'php', event)">php</button>
                                        &nbsp;
                                        <button class="btn btn-info btn-xs" OnClick="projects.export({{id_project}},'yaml', event)">yaml</button>
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-danger btn-xs"
                                            id="projectButtonDelete_{{id_project}}"
                                            OnClick="projects.deleteProject({{id_project}})"><?php echo $i18n['layout']['delete']; ?></button>
                                    </td>
                                </tr>
                            {{/projects}}

                            {{^projects}}
                                <tr>
                                    <td colspan="4">{{i18n.no_projects}}</td>
                                </tr>
                            {{/projects}}

                        </tbody>
                    </table>
                </script>

                <h3><?php echo $i18n['layout']['add_project']; ?></h3>
                <hr>
                <div id="projectFormBlock"></div>
                <script id="projectFormTemplate" type="x-tmpl-mustache">
                    <form id="projectForm" class="form-horizontal">
                        {{#id_project}}
                            <input type="hidden" name="id_project" value="{{id_project}}">
                        {{/id_project}}

                    <div class="form-group">
                        <label class="col-sm-6 control-label"><?php echo $i18n['layout']['name']; ?></label>
                        <div class="col-sm-6">
                            <input class="form-control input-sm" type="text" name="name" id="projectInputName" value="{{name}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label"><?php echo $i18n['layout']['languages']; ?></label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="languages" value="{{languages}}" id="projectInputLanguages" placeholder="en,ru,de">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label"><?php echo $i18n['layout']['path']; ?></label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="path" value="{{path}}" id="projectInputPath" placeholder="./">
                        </div>
                    </div>
                    <button type="button" id="projectButtonSave" onclick="projects.ProjectForm.save()" class="btn btn-success"><?php echo $i18n['layout']['save']; ?></button>
                    <button type="reset" onclick="projects.ProjectForm.render()" class="btn btn-default"><?php echo $i18n['layout']['clear']; ?></button>
                </form>
                </script>

            </div>
            <div class="col-md-1"></div>
            <div class="col-md-2">
                <input type="text" class="form-control" id="searchKeyword" onkeyup="codes.SearchField.find($(this).val())" placeholder="<?php echo $i18n['layout']['code_placeholder']; ?>">
                <div id="codesBlock"></div>
                <script id="codesTemplate" type="x-tmpl-mustache">
                    <table class="table table-condensed">
                        <thead>
                            <th><?php echo $i18n['layout']['code']; ?></th>
                            <th><?php echo $i18n['layout']['manage']; ?></th>
                        </thead>
                        <tbody>
                            {{#codes}}
                                <tr OnClick="codes.selectCode('{{code}}', $(this))" class="code_block" id="codeButtonSelect_{{code}}">
                                    <td>{{code}}</td>
                                    <td><button class="btn btn-danger btn-xs" id="codeButtonDelete_{{code}}" OnClick="codes.deleteCode('{{code}}', $('#searchKeyword').val())"><?php echo $i18n['layout']['delete']; ?></button></td>
                                </tr>
                            {{/codes}}
                        </tbody>
                    </table>
                </script>

            </div>
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <div id="translationFormBlock"></div>
                <script id="translationFormTemplate" type="x-tmpl-mustache">
                    {{#id_project}}
                        <div id="newTranslationButton">
                            <button type="button" onclick="translation.render()" class="btn btn-default"><?php echo $i18n['layout']['new_translation']; ?></button>
                        </div>

                        <form id="translationForm" class="form-horizontal">
                            <input type="hidden" name="id_project" value="{{id_project}}">

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $i18n['layout']['code']; ?>:</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="code" id="codeInputCode" value="{{code}}" required>
                                </div>
                            </div>

                            {{#translations}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">{{language}}:</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" id="codeInputLanguage{{language}}" name="translation[{{language}}]">{{translation}}</textarea>
                                    </div>
                                </div>
                            {{/translations}}

                            <button type="button" id="codeButtonSave" onclick="translation.save($('#code').val())" class="btn btn-success"><?php echo $i18n['layout']['save']; ?></button>

                        </form>
                    {{/id_project}}
                </script>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <hr>
            </div>
        </div>
        <div class="row">

            <div class="col-md-10">
                &nbsp;
            </div>
            <div class="col-md-2">
                <a href="https://github.com/serkin/foler" target="_blank">github</a>
            </div>

        </div>
        <script>
            url = '<?php echo $app["config"]["url"]; ?>';
        </script>

        <!-- Here goes content from all js/*.js files -->
        <script>


function sendRequest(action, data, callback) {

    $.ajax({
            type: 'post',
            url: url + '?action='+action,
            data: data,
            error: function()	{
                alert('Connection lost');
            },
            success: function(response)	{
                if(callback !== "undefined"){
                    callback(response);
                }
            }
        });
}


var codes = {

    deleteCode: function(code, searchKeyword) {
        sendRequest('code/delete', {code:code, id_project: idSelectedProject}, function(response){

            statusField.render(response.status);
            codes.SearchField.find(searchKeyword);
            translation.render();

        });

    },

    selectCode: function(code, el) {
        $('.code_block').removeClass('success');
        el.addClass('success');
        translation.render(code);
    },

    CodeForm: {
        save:   function(code) {
            sendRequest('code/save', {code:code, id_project: idSelectedProject});
        },
        render: function() {

            var template = $('#codeFormTemplate').html();
            var rendered = Mustache.render(template);

            $('#codeFormBlock').html(rendered);
        },
        hide:   function() {
            $('#codeFormBlock').html('');
        }
    },


    SearchField: {

        find:   function(keyword) {
            sendRequest('code/search', {keyword:keyword, id_project: idSelectedProject}, function(response){

            var template = $('#codesTemplate').html();
            var rendered = Mustache.render(template, response.data);

            $('#codesBlock').html(rendered);
        });
        },
        show: function() {
            $('#searchKeyword').show();
        },
        hide: function() {
            $('#searchKeyword').hide();
        }

    }
};

var locale = 'en';
var i18n = {
    en: {
        no_projects: 'No projects yet. Start with adding new project in the form below',
        no_codes: 'No codes found.',
    }
};

i18n = i18n[locale];




var idSelectedProject;
var projects = {
    deleteProject: function(idProject) {

        sendRequest('project/delete', {id_project: idProject}, function(response){
            statusField.render(response.status);
            translation.render();
            projects.reload();
            idSelectedProject = null;

        });
    },
    reload: function() {
        sendRequest('project/getall',{}, function(response){

            response.data.i18n = i18n;
            var template = $('#projectsTemplate').html();
            var rendered = Mustache.render(template, response.data);

            $('#projectsBlock').html(rendered);
        });

        projects.ProjectForm.render();
    },

    selectProjectById: function(idProject) {

        $('#idGlobalProject').val(idProject);
        $('.project_block').removeClass('success');
        $('#project_block_' + idProject).addClass('success');
        idSelectedProject = parseInt(idProject);
        projects.ProjectForm.render(idSelectedProject);

        translation.render();
        codes.SearchField.show();

    },
    export: function(idProject, type, ev) {
        sendRequest('project/export', {id_project: idProject, type: type}, function(response){
                statusField.render(response.status);
        });
        ev.stopPropagation();
    },


    ProjectForm: {
        save: function(){
            var data = $('#projectForm').serialize();

            sendRequest('project/save', {form: data}, function(response){

                statusField.render(response.status);

                if(response.status.state === 'Ok'){
                    projects.reload();

                    var id = parseInt(response.data.id_project);

                    if(id > 0){
                        projects.selectProjectById(id);
                    }
                }

            });
        },

        render: function(idProject) {

            var template = $('#projectFormTemplate').html();

            if(idProject === undefined) {

                var rendered = Mustache.render(template);
                $('#projectFormBlock').html(rendered);

            } else {

                sendRequest('project/getone',{id_project:idProject}, function(response){

                    var rendered = Mustache.render(template, response.data.project);
                    $('#projectFormBlock').html(rendered);
                });
            }
        }
    }
};


var statusField = {
    el: $('#status_field'),
    setFail: function(message) {
        this.el.html('<p class="bg-danger">'+message+'</p>');
        setTimeout(function() {
            statusField.clear();
        }, 5000);
    },

    setOk: function(message) {
        this.el.html('<p class="bg-success">'+message+'</p>');
        setTimeout(function() {
            statusField.clear();
        }, 5000);
    },

    clear: function() {
        this.el.html('');
    },

    render: function(status) {

        if(status.state === 'Ok') {
            this.setOk(status.message);
        }

        if(status.state === 'notOk') {
            this.setFail(status.message);
        }
    }
};



var translation = {
    save: function(code) {

        var data = $('#translationForm').serialize();

        sendRequest('translation/save', {form:data}, function(response){
            statusField.render(response.status);
            translation.render(code);
        });

    },

    render: function(code) {

        var data = (code !== "undefined") ? {code: code, id_project:idSelectedProject} : {id_project:idSelectedProject};

        sendRequest('translation/getone', data, function(response){

            response.data.id_project = idSelectedProject;

            var template = $('#translationFormTemplate').html();
            var rendered = Mustache.render(template, response.data);

            $('#translationFormBlock').html(rendered);
        });

    }
};

</script>
        <!-- /end -->

    </body>
</html>
