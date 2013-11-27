/*
Function that helps implement inheritance in JavaScript.
See http://javascript.ru/tutorial/object/inheritance
 */
function extend(Child, Parent) {
	var F = function() {};
	F.prototype = Parent.prototype;
	Child.prototype = new F();
	Child.prototype.constructor = Child;
	Child.superclass = Parent.prototype;
}

/*
This function counts number of an object's own properties
*/
function objectPropertiesCount(obj) {
	var count = 0;
	for (var name in obj) {
		if (!obj.hasOwnProperty(name) || typeof(obj[name]) === 'function') {
			continue;
		}
		count++;
	}
	return count;
}

/*
 Return name of "class" (actually, JavaScript object prototype of which object is a copy)
*/
function getClassName(obj) {
	var c = obj.constructor.toString();
	return c.substring(c.indexOf('function ')+9, c.indexOf('('));
}

/*
 Modification to $.unique() that preserves it's existing functionality
 while making it work on arrays containing anything (instead of just arrays containing DOM elements).
 */

(function($){

	var _old = $.unique;

	$.unique = function(arr){

		// do the default behavior only if we got an array of elements
		if (arr.length > 0 && !!arr[0].nodeType){
			return _old.apply(this,arguments);
		} else {
			// reduce the array to contain no dupes via grep/inArray
			return $.grep(arr,function(v,k){
				return $.inArray(v,arr) === k;
			});
		}
	};
})(jQuery);

function scrollPageIfInvisible(scrollToX) {
    if (scrollToX < $(window).scrollTop() || scrollToX > $(window).scrollTop() + $(window).height()*0.3 ) {
        $('html,body').animate({scrollTop: scrollToX});
    }
}

/* http://phpjs.org/functions/base64_encode/ */
function base64_encode (data) {
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = "",
        tmp_arr = [];

    if (!data) {
        return data;
    }

    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1 << 16 | o2 << 8 | o3;

        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);

    enc = tmp_arr.join('');

    var r = data.length % 3;

    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);

}

/* http://phpjs.org/functions/base64_decode/ */
function base64_decode (data) {
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        dec = "",
        tmp_arr = [];

    if (!data) {
        return data;
    }

    data += '';

    do { // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));

        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;

        if (h3 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);

    dec = tmp_arr.join('');

    return dec;
}