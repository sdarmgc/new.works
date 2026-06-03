<?php

/* The substitutions are:
1. Replacing the space after vol. /p. /bk. /no. with <0X00A0> non-breaking space
"((vol)|(p{1,2})|(bk)|(no))\.\s" => "$1<0x00A0>",
2. Replacing space between . . . with non-breaking space
3. Replacing hyphen between two numbers with endash <0X2013>
4. Replacing space between numbered Bible book names (like 1 John, 2 John etc.) with non-breaking space
	"(\d)\s([a-zA-Z]+)\W" => "$1<0x00A0>$2 ",
5. Replace space between ’ ” with non-breaking space.
*/

$dnSubstitute = array(

	"(p|pp|vol|bk|no)\.\s(\d+)" => "$1.<0x00A0>$2",
	"( \. \. \.)" => " .<0x00A0>.<0x00A0>.",
	"(\d)\-(\d)" => "$1<0x2013>$2",
	"(\d)\s(Chronicles|Corinthians|John|Kings|Peter|Samuel|Thessalonians|Timothy)" => "$1<0x00A0>$2",
	"(’) (”)" => "$1<0x00A0>$2"
	
);