package com.cfm 
{
	import flash.display.Bitmap;
	import flash.display.MovieClip;
	import flash.display.Sprite;

	public class VideoTrackers extends MovieClip 
	{
		public var trackMask : MovieClip;
		public var sword : MovieClip;
		public var flea1 : MovieClip;
		public var flea2 : MovieClip;
		public var fleaEnd : MovieClip;
		public var tick1 : MovieClip;
		public var tick2 : MovieClip;
		private var _trackers : Array;
		
		public function VideoTrackers()
		{
			//this should match the _dropZones array in Assign.as
			_trackers = [sword, flea1, flea2, tick1, tick2, fleaEnd];
		}
		
		public function disableUnusedTrackers() : void
		{
			var n : int = _trackers.length;
			for (var i:int = 0; i < n; i++)
			{
			 	var mc : MovieClip = MovieClip(_trackers[i]);
			 	if(mc.numChildren < 1) mc.visible = false;   
			}
		}

		public function addHead(bmp : Bitmap, trackIndex : int) : void
		{			
			trace("VideoTrackers: addHead: trackIndex: ", trackIndex);
			var holder : Sprite = new Sprite();
			holder.addChild(bmp);
			bmp.x = 0 - bmp.width * 0.5;
			bmp.y = 0 - bmp.height * 0.5 - 30;
			bmp.smoothing = true;
			
			holder.scaleX = 0.5;
			holder.scaleY = 0.5;
			
			var tracker : MovieClip = _trackers[trackIndex] as MovieClip;

			while(tracker.numChildren > 0) tracker.removeChildAt(0);
			
			tracker.addChild(holder);
			
			//we want to copy the head used for any character except sword and apply it to fleaEnd, in case flea1 is never assigned
			if(trackIndex > 1 && flea1.numChildren < 1 && fleaEnd.numChildren < 1)
			{
				trace("copying head from: ", trackIndex, ", to fleaEnd");
				addHead(new Bitmap(bmp.bitmapData.clone()), _trackers.length-1);
			}
			
			//if flea1 is assigned, we want to disable fleaEnd
			if(tracker == flea1 && fleaEnd.numChildren > 0)
			{
				trace("disabling fleaEnd");
				while(fleaEnd.numChildren > 0) fleaEnd.removeChildAt(0);
				fleaEnd.visible = false;
			}
		}
	}
}
