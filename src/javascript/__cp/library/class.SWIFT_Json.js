/**
 *  Json helper class
 *
 * @author Atul Atri<atul.atri@kayko.com>
 * @class
 * @extends SWIFT_BaseClass
 */
SWIFT.Library.Json = SWIFT.Base.extend({
	//josn object
	json:    null,
	//raw json string
	jsonStr: null,

	/**
	 * Parse json from string
	 *
	 * @param {String} jsonStr string to be parsed
	 *
	 *  @return SWIFT.Basecamp.Json or false if string could not be parsed into json
	 */
	Parse: function(jsonStr) {
		try {
			jsonStr = jsonStr.substr(11);

			this.json = $.parseJSON(jsonStr);
		} catch (err) {
			return false;
		}

		return this;
	},

	/**
	 * Initialise system
	 *
	 * @param {String} jsonStr string to be parsed
	 *
	 *  @return SWIFT.Basecamp.Json or false if string could not be parsed into json
	 */
	init: function(jsonStr) {
		return this.Parse(jsonStr);
	},

	/**
	 * Get the success code
	 *
	 *  @return {integer} response code
	 */
	GetSuccessCode: function() {
		if (this.json.responseCode) {
			return this.json.responseCode;
		}

		return 0;
	},

	/**
	 * Get Messages
	 *
	 *  @return {Array} messages
	 */
	GetMessages: function() {
		if (this.json.responseCode) {
			return this.json.responseCode;
		}

		return [];
	},

	/**
	 * Get Body
	 *
	 *  @return {Object} object
	 */
	GetData: function() {
		if (this.json.data) {
			return this.json.data;
		}

		return {};
	}
});