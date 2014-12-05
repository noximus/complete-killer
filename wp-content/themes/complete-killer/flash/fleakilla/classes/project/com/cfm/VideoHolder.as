package com.cfm 
{
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.display.MovieClip;
	import com.greensock.TweenLite;

	public class VideoHolder extends MovieClip 
	{
		public var overlay : MovieClip;
		public var trackers : VideoTrackers;
		public var player : FleaPlayer;
		public var trackMask : MovieClip;
		
		public function VideoHolder()
		{
			init();
		}
		
		private function init() : void
		{
			trackers.mask = trackMask;
			trackMask.visible = false;
			trackers.visible = false;
			
			player.setTrackerClip(trackers);
			player.addEventListener(FleaPlayer.START, hideOverlay, false, 0, true);
			player.addEventListener(FleaPlayer.STOP, showOverlay, false, 0, true);
			player.video.visible = false;			
			
			overlay.playIcon.addEventListener(MouseEvent.CLICK, overlayClicked, false, 0, true);
			overlay.playIcon.buttonMode = true;
			
			//delay until the view has been faded in
			TweenLite.delayedCall(2, activate);
		}
		
		private function activate() : void
		{
			player.video.visible = true;
			trackers.visible = true;
		}
		
		private function showOverlay(event : Event) : void
		{
			overlay.visible = true;
		}
		
		private function hideOverlay(event : Event) : void
		{	
			overlay.visible = false;
		}

		private function overlayClicked(event : MouseEvent) : void
		{
			player.start();
		}
	}
}
