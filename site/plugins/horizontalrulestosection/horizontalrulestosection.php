<?php
// filters which are called AFTER markdown and tags are parsed
kirbytext::$pre[] = function($kirbytext, $value) {
  // your filter code
  $sectionCode = '</section><section class="module--projet--text">';
  return str_replace( '---', $sectionCode, $value);
};