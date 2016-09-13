"use strict";


QUnit.test( "essp test", function( a ) {
	a.ok( true == true, "true" );

	///innerTrim timetest
	function innerTrim1(str) {//10K=330ms
		///original
		return str
			.split(String.fromCharCode(32))
			.filter(function(c){return c})
			.join(" ")
			.split(String.fromCharCode(9))
			.filter(function(c){return c})
			.join(" ");
	}

	function innerTrim2(str) {//10K=75ms
		///replace filter
		var arr = str.split(String.fromCharCode(32));
		var arr2 = [];
		for (var i=0; i < arr.length; i++) {
			if (arr[i]) {
				var a = arr[i].split(String.fromCharCode(9));
				for (var j=0; j < a.length; j++) {
					if (a[j])
						arr2.push(a[j]);
				}
			}
		}
		return arr2.join(" ");
	}

	function innerTrim3(str) {//10K=68ms
		///replace join
		var arr = str.split(String.fromCharCode(32));
		var arr2 = [];
		for (var i=0; i < arr.length; i++) {
			if (arr[i]) {
				var a = arr[i].split(String.fromCharCode(9));
				for (var j=0; j < a.length; j++) {
					if (a[j])
						arr2.push(a[j]);
				}
			}
		}

		var s = "";
		for (var i=0; i < arr2.length; i++) {
			s += arr2[i] + " ";
		}
		return s;
	}

	function innerTrim4(str) {//10K=42ms
		///replace split
		var arr = _split(str, String.fromCharCode(32));
		var arr2 = [];
		for (var i=0; i < arr.length; i++) {
			if (arr[i]) {
				var a = _split(arr[i], String.fromCharCode(9));
				for (var j=0; j < a.length; j++) {
					if (a[j])
						arr2.push(a[j]);
				}
			}
		}
		
		var s = "";
		for (var i=0; i < arr2.length; i++) {
			s += arr2[i] + " ";
		}
		return s;
	}

	function _split(s, d) {
		var arr = [];
		var c = "";
		for (var i=0; i < s.length; i++) {
			if (i == s.length-1)
				c += s[i];

			if (s[i] == d || i == s.length-1) {
				arr.push(c);
				c = "";
			} else {
				c += s[i];
			}
		}
		return arr;
	}

	function innerTrim5(str) {//10K=16ms !!!without defects full clean with first and last spaces
		//line algorythm
		var s = "";
		var c = "";

		for (var i=0; i < str.length; i++) {
			var isSpace = (str[i] == String.fromCharCode(32) || str[i] == String.fromCharCode(9));
			
			if (isSpace) {
				c = " ";
				if (!s) c = "";
			} else {
				c += str[i];
				s += c;
				c = "";
			}
		}
		
		return s;
	}

	function innerTrim6(str) {//10K=16ms with defect - last space not deleted
		//line algorythm
		var s = "";
		var c = "";
		var isSpaceOld = true;
		
		for (var i=0; i < str.length; i++) {
			var isSpace = (str[i] == String.fromCharCode(32) || str[i] == String.fromCharCode(9));

			if (!isSpace) s += str[i];
			if (isSpace && !isSpaceOld) s += " ";
			
			isSpaceOld = isSpace;
		}
		
		return s;
	}

	function timetestcase(func, arg, count) {
		var d1 = new Date();
		var n = count;

		for (var i=0; i < n; i++) {
			func(arg);
		}

		var d2 = new Date() - d1;
		return d2;
	}

	function timetest(func, arg, count) {
		var sum = 0;
		var n = 10;

		for (var i=0; i < n; i++) {
			sum += timetestcase(func, arg, count);
		}

		return sum/n;

	}

	function innerTrimTT() {
		var funcs = [innerTrim1, innerTrim2, innerTrim3, innerTrim4, innerTrim5, innerTrim6];
		var arg = "a	b  c";
		for (var i=0; i < funcs.length; i++) {
			console.log("100 000 x innerTrim"+(i+1) + ": " + timetest(funcs[i], arg, 100000) + "ms");
		}
	}

	///////////
	
});




