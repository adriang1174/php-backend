function waitForImage(holder, img, w, h, b,loading) {
        var str = "";
        var maxWidth = w - (b*2);
        var maxHeight = h  - (b*2);
        var width,height,ratio;
        
	if(!img.complete) {

		setTimeout(function() {
			waitForImage(holder, img, w, h, b);
		}, 1000);

		return;

	}


        width = img.width;
        height = img.height;

        if(width > maxWidth){
            ratio = maxWidth / width;   // get ratio for scaling image
            img.width = maxWidth; // Set new width
            //$(this).prev().css("width", maxWidth);
            img.height = height * ratio;
            //$(this).css("height", height * ratio);  // Scale height based on ratio
            //$(this).prev().css("height", height * ratio);
            height = height * ratio;    // Reset height to match scaled image
            width = width * ratio;    // Reset width to match scaled image
        }

        // Check if current height is larger than max
        if(height > maxHeight){
            ratio = maxHeight / height; // get ratio for scaling image
            img.height = maxHeight;   // Set new height
            //$(this).prev().css("height", maxHeight);   // Set new height
            img.width = width * ratio;    // Scale width based on ratio
            //$(this).prev().css("width", width * ratio);    // Scale width based on ratio
            width = width * ratio;    // Reset width to match scaled image
            height = maxHeight;
        }
        
        
        
        
        $("#" + holder).removeClass(loading);
	$("#" + holder).prepend(img);
	$("#" + holder + " img").css({
		"display": "block",
		"left": "50%",
		"margin-left": -(width / 2) + "px",
		"margin-top": -(height / 2) + "px",
		"position": "relative",
		"top": "50%"
	});
        

}