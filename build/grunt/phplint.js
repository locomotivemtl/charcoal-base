module.exports = {
	options: {
		swapPath: '/tmp',
		phpArgs : {
			// add -f for fatal errors
			'-lf': null
		}
	},

	src: [
		'src/**/*.php'
	],
	tests: [
		'tests/**/*.php'
	]
};
