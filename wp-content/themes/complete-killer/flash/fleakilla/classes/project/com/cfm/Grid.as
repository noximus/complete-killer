package com.cfm
{
	import flash.display.*;
	import flash.geom.Point;
	
	public class Grid extends Sprite
	{
		private static var _points:Array;
		
		public function Grid()
		{
			//Nothing to see here, its static!
		}
		
		public static function build(rows,cols,margin,w,h):Array
		{
			_points = new Array();
			
			for(var i=0; i<rows; i++)
			{
				for(var j=0; j<cols; j++)
				{
					var pt:Point = new Point();
					pt.x = j * (margin + w);
					pt.y = i * (margin + h);
					_points.push(pt);
				}
			}
			
			return _points;
		}
	}
}