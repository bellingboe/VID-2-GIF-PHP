<?php
include "ImageTools.class.php";
include "GIFEncoder.class.php";
/*
    Build a frames array from sources...
if ( $dh = opendir ( "frames3/" ) ) {
    while ( false !== ( $dat = readdir ( $dh ) ) ) {
        if ( $dat != "." && $dat != ".." ) {
		$fn = ImageTools::toGif("frames3/$dat");
		$frames [ ] = $fn;
		$framed [ ] = 10;
        }
    }
    closedir ( $dh );
}
*/

$sd = scandir ( "frames3/" );
natsort($sd);

foreach ($sd as $s) {
    if ( $s != "." && $s != ".." ) {
	    $fn = ImageTools::toGif("frames3/$s");
	    $frames [ ] = $fn;
	    $framed [ ] = 10;
    }
}

/*
	GIFEncoder constructor:
	=======================
	
	image_stream = new GIFEncoder    (
			    URL or Binary data    'Sources'
			    int                    'Delay times'
			    int                    'Animation loops'
			    int                    'Disposal'
			    int                    'Transparent red, green, blue colors'
			    int                    'Source type'
			);
*/
$gif = new GIFEncoder    (
                            $frames,
                            $framed,
                            0,
                            2,
                            0, 0, 0,
                            "url"
        );
/*
        Possibles outputs:
        ==================

        Output as GIF for browsers :
            - Header ( 'Content-type:image/gif' );
        Output as GIF for browsers with filename:
            - Header ( 'Content-disposition:Attachment;filename=myanimation.gif');
        Output as file to store into a specified file:
            - FWrite ( FOpen ( "myanimation.gif", "wb" ), $gif->GetAnimation ( ) );
*/
header('Content-Type:image/gif');
echo $gif->GetAnimation();
exit;