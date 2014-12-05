package com.cfm 
{
	import fl.controls.TextInput;
	import flash.text.TextField;
	import flash.display.MovieClip;

	public class InputPair extends MovieClip 
	{
		public var nameLabel : TextField;
		public var emailLabel : TextField;
		public var txtName : TextInput;
		public var txtEmail : TextInput;
		
		public function InputPair(nameLbl : String, emailLbl : String)
		{
			nameLabel.text = nameLbl;
			emailLabel.text = emailLbl;
			clear();
		}
		
		public function clear() : void
		{
			txtName.text = "";
			txtEmail.text = "";
		}

		public function getName() : String
		{
			return txtName.text;
		}

		public function getEmail() : String
		{
			return txtEmail.text;
		}

		public function hasData() : Boolean
		{
			return txtName.text != "" && txtEmail.text != "";
		}
		
		public function getObject() : Object
		{
			return {friendName:txtName.text, friendEmail:txtEmail.text};
		}
	}
}
