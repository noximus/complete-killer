package com.cfm 
{
	import flash.display.Bitmap;

	public class UserData 
	{
		public var position1 : Bitmap;
		public var position2 : Bitmap;
		public var position3 : Bitmap;
		public var position4 : Bitmap;
		public var position5 : Bitmap;
		public var assignments : Array;
		
		public function UserData()
		{
			assignments = [position1, position2, position3, position4, position5];	
		}
		
		public function assignBitmap(bitmap : Bitmap, assignedIndex : int) : void
		{
			assignments[assignedIndex] = bitmap;
		}
	}
}
