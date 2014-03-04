var SITE = SITE || {};
 
SITE.fileInputs = function() {
  var $this = $(this),
      $parent = $this.parent(),
      $val = $this.val(),
      valArray = $val.split('\\'),
      newVal = valArray[valArray.length-1],
      $button = $parent.find('.file-button'),
      $fakeFile = $parent.parent().find('.file-holder');
  if(newVal !== '') {
    $button.text('Файл выбран');
    if($fakeFile.length === 0) {
      $('.file-holder').text(newVal);
    } else {
      $fakeFile.text(truncate(newVal, 30));
    }
  }
};
 
$(document).ready(function() {
  $('.file-wrapper input[type=file]').bind('change', SITE.fileInputs);
});

function truncate(fullStr, strLen) {
	var separator = '…';
	
    if (fullStr.length <= strLen) return fullStr;
    
    var sepLen = separator.length,
        charsToShow = strLen - sepLen,
        frontChars = Math.ceil(charsToShow - 7),
        backChars = 7;
    
    return fullStr.substr(0, frontChars) + 
        separator + 
        fullStr.substr(fullStr.length - backChars);
}