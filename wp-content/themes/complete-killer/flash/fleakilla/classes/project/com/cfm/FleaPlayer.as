package com.cfm 
{
	import flash.events.Event;
	import flash.media.SoundTransform;
	import flash.events.TimerEvent;
	import flash.utils.Timer;
	import flash.events.MouseEvent;
	import flash.accessibility.Accessibility;
	import flash.events.AsyncErrorEvent;
	import flash.events.NetStatusEvent;
	import flash.net.NetStream;
	import flash.net.NetConnection;
	import flash.media.Video;
	import flash.display.MovieClip;
	import com.greensock.TweenLite;

	public class FleaPlayer extends MovieClip 
	{
		public static const START : String = "START";
		public static const STOP : String = "STOP";
		
		public var playToggle : MovieClip;
		public var muteToggle : MovieClip;
		public var videoProgress : MovieClip;
		public var progressBar : MovieClip;
		public var playhead : MovieClip;
		public var video : Video;
		
		private var _videoWidth : Number = 600;
		private var _videoHeight : Number = 336;
		private var _duration : Number;
		
		private var _startBufferTime : Number = 6;
		private var _extraBufferTime : Number = 15;
		
		private var _stream : NetStream;
		private var _timer : Timer;
		
		private var _newMeta : Object;
		private var _isMuted : Boolean = false;
		private var _isPlaying : Boolean = false;
		
		private var _percentPlayed : Number;
		
		private var _soundTransform : SoundTransform;
		private var _trackers : VideoTrackers;
		private var _trackerFrames : int;
		
		public function FleaPlayer()
		{
			init();
		}

		private function init() : void
		{
			progressBar = videoProgress.progressBar;
			playhead = videoProgress.playhead;
			
			progressBar.width = 1;
			
			initVideo();
			
			playToggle.addEventListener(MouseEvent.CLICK, playToggleHandler, false, 0, true);
			playToggle.buttonMode = true;
			playToggle.pauseIcon.visible = false;
			
			muteToggle.addEventListener(MouseEvent.CLICK, muteToggleHandler, false, 0, true);
			muteToggle.buttonMode = true;
			muteToggle.disabledIcon.visible = false;
			_isMuted = false;
			
			_timer = new Timer(150);
			_timer.addEventListener(TimerEvent.TIMER, timerHandler, false, 0, true);
			

			_soundTransform = new SoundTransform(1);
			_stream.soundTransform = _soundTransform;
		}
		
		private function initVideo() : void
		{
			video = new Video(_videoWidth, _videoHeight);
			video.width = _videoWidth;
			video.height = _videoHeight;
			addChild(video);
			
			var nc : NetConnection = new NetConnection();
			nc.connect(null);
			_stream = new NetStream(nc);
			_stream.addEventListener(NetStatusEvent.NET_STATUS, statusHandler, false, 0, true);
			
			video.attachNetStream(_stream);
			_newMeta = new Object();
			_newMeta.onMetaData = metaDataHandler;
			_stream.client = _newMeta;
			_stream.bufferTime = _startBufferTime;
		}

		private function timerHandler(event : TimerEvent = null) : void
		{
			_percentPlayed = _stream.time / _duration;
			_trackers.gotoAndPlay(_trackers.currentFrame + int(_trackerFrames * _percentPlayed) - _trackers.currentFrame);
			playhead.x = _percentPlayed * 400;
			progressBar.width = playhead.x;
		}
		
		public function start() : void
		{
			playToggleHandler();
		}

		private function playToggleHandler(event : MouseEvent = null) : void
		{
			trace("FleaPlayer: playToggleHandler: _isPlaying: ", _isPlaying);
			if(_isPlaying)
			{
				pausePlayback();
			}
			else
			{
				resumePlayback();
			}
			playToggle.pauseIcon.visible = _isPlaying;
			playToggle.playIcon.visible = !_isPlaying;
		}

		private function pausePlayback() : void
		{
			_stream.pause();
			_timer.stop();
			
			timerHandler();
			_trackers.stop();
			
			_isPlaying = false;
		}

		private function resumePlayback() : void
		{
			dispatchEvent(new Event(FleaPlayer.START));
			timerHandler();
			
			_trackers.play();
			_stream.resume();
			_timer.start();
			
			_isPlaying = true;
		}

		private function stopPlayback() : void
		{
			dispatchEvent(new Event(FleaPlayer.STOP));
			_stream.pause();
			_stream.seek(0);
			_timer.stop();
			playhead.x = 0;
			progressBar.width = 0;
			_trackers.gotoAndStop(2);

			_isPlaying = false;
			playToggle.pauseIcon.visible = false;
			playToggle.playIcon.visible = true;
		}
		
		public function destroy() : void
		{
			video.visible = false;
			_trackers.visible = false;
			stopPlayback();
			playToggle.removeEventListener(MouseEvent.CLICK, playToggleHandler);
			muteToggle.removeEventListener(MouseEvent.CLICK, muteToggleHandler);
			_timer.removeEventListener(TimerEvent.TIMER, timerHandler);
			_timer.reset();
			_stream.removeEventListener(NetStatusEvent.NET_STATUS, statusHandler);
		}

		private function muteToggleHandler(event : MouseEvent) : void
		{
			_isMuted = !_isMuted;
			muteToggle.disabledIcon.visible = _isMuted;
			_soundTransform.volume = _isMuted ? 0 : 1;
			_stream.soundTransform = _soundTransform;
		}

		private function statusHandler(event : NetStatusEvent) : void
		{
			//trace("FleaPlayer: statusHandler: event.info.code: ", event.info.code);
			switch(event.info.code)
			{
				case "NetStream.Connect.Success":
					break;
				case "NetStream.Buffer.Full":
					_stream.bufferTime = _extraBufferTime;
					break;
				case "NetStream.Buffer.Empty":
					_stream.bufferTime = _startBufferTime;
					break;
				case "NetStream.Play.Start":
					TweenLite.to(_trackers, 0.4, {delay:0.4, alpha:1});
					break;
				case "NetStream.Play.Stop":
					stopPlayback();
					break;
				default:
					break;
			}
		}
		
		private function metaDataHandler(newMeta : Object) : void
		{
			trace("Metadata: duration=" + newMeta.duration + " width=" + newMeta.width + " height=" + newMeta.height + " framerate=" + newMeta.framerate);  // traces what it says
			_duration = newMeta.duration;
		}
		
		public function playClip(path : String) : void
		{
			_stream.play(path);
			stopPlayback();
		}
		
		public function setTrackerClip(trackerClips : VideoTrackers) : void
		{
			_trackers = trackerClips;
			_trackers.alpha = 0;
			_trackerFrames = _trackers.totalFrames;
		}
	}	
}