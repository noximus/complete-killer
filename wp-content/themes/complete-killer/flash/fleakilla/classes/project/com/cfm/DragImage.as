package com.cfm 
{
	import flash.display.Bitmap;
	import flash.display.Sprite;

	public class DragImage extends Sprite 
	{
		private var _bmp : Bitmap;
		private var _imageIndex : int = -1;

		public function DragImage(imageIndex : int, sourceBitmap : Bitmap)
		{
			_imageIndex = imageIndex;
			
			_bmp = sourceBitmap;
			_bmp.x = 0 - _bmp.width * 0.5;
			_bmp.y = 0 - _bmp.height * 0.5;
			_bmp.smoothing = true;
			
			addChild(sourceBitmap);
			
			buttonMode = true;
		}

		public function destroy() : void
		{
			_bmp.bitmapData.dispose();
			_bmp = null;
		}
		
		public function get bmp() : Bitmap
		{
			return _bmp;
		}
		
		public function get imageIndex() : int
		{
			return _imageIndex;
		}
	}
}
