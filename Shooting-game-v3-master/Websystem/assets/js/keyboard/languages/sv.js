// Keyboard Language
// please update this section to match this language and email me with corrections!
// sv = ISO 639-1 code for Swedish
// ***********************
jQuery.keyboard.language.sv = {
	language: 'Svenska (Swedish)',
	display : {
		'a'      : '\u2714:Acceptera (Shift+Enter)', // check mark - same action as accept
		'accept' : 'Acceptera:Acceptera (Shift+Enter)',
		'alt'    : 'AltGr:Alternate Grafem',
		'b'      : '\u2190:Backspace',    // Left arrow (same as &larr;)
		'bksp'   : 'Bksp:Backspace',
		'c'      : '\u2716:Avbryt (Esc)', // big X, close - same action as cancel
		'cancel' : 'Avbryt:Avbryt (Esc)',
		'clear'  : 'C:Rensa',             // clear num pad
		'combo'  : '\u00f6:Växla Kombo Tangenter',
		'dec'    : '.:Decimal',           // decimal point for num pad (optional), change '.' to ',' for European format
		'e'      : '\u21b5:Enter',        // down, then left arrow - enter symbol
		'enter'  : 'Enter:Enter',
		'lock'   : '\u21ea Lock:Caps Lock', // caps lock
		's'      : '\u21e7:Shift',        // thick hollow up arrow
		'shift'  : 'Shift:Shift',
		'sign'   : '\u00b1:Ändra Tecken',  // +/- sign for num pad
		'space'  : '&nbsp;:Space',
		't'      : '\u21e5:Tab',          // right arrow to bar (used since this virtual keyboard works with one directional tabs)
		'tab'    : '\u21e5 Tab:Tab'       // \u21b9 is the true tab symbol (left & right arrows)
	},
	// Message added to the key title while hovering, if the mousewheel plugin exists
	wheelMessage : 'Använd mus hjulet för att se andra tangenter',
};
