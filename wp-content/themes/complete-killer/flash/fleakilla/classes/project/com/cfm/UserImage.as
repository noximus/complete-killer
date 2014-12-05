package com.cfm 
{
	import flash.display.Bitmap;

	public class UserImage 
	{
		private var _imageID : String;
		private var _bitmap : Bitmap;
		private var _assignedChar : int = -1;

		public function UserImage(imageID : String)
		{
			_imageID = imageID;
		}
		
		public function addBitmap(bmp : Bitmap) : void
		{
			_bitmap = bmp;
		}
		
		public function get imageID() : String
		{
			return _imageID;
		}
		
		public function get bitmap() : Bitmap
		{
			return _bitmap;
		}
		
		public function isAssigned() : Boolean
		{
			return _assignedChar > -1;
		}
		
		public function get assignedChar() : int
		{
			return _assignedChar;
		}
		
		public function set assignedChar(assignedChar : int) : void
		{
			_assignedChar = assignedChar;
		}
	}
}
