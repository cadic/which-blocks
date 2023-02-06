const defaultEslintrc = require('10up-toolkit/config/.eslintrc');

module.exports = {
	...defaultEslintrc,
	rules: {
		...defaultEslintrc.rules,
		'jsdoc/check-tag-names': [
			'error',
			{
				definedTags: ['filter', 'action'],
			},
		],
		'no-console': 0,
		'no-undef': 0,
		'jest/expect-expect': 0,
	},
};
