if [[ $1 == '' ]]; then
	echo 'Please define a name for the pattern';
	exit 1;
fi;

touch cypress/integration/$1.spec.js
echo "context('$1', () => {
	before(() => {
		cy.visit('http://localhost:3000/pattern-library')
	});

	describe('$1', () => {
		it('should do something', () => {
			// start writing tests here
		});
	});
});" > cypress/integration/$1.spec.js

mkdir resources/views/patterns/$1
touch resources/views/patterns/$1/$1.json
touch resources/views/patterns/$1/$1.blade.php

echo "{
	\"title\": \"\",
	\"description\": \"\",
	\"instances\": [],
	\"testFile\": \"cypress/integration/$1.spec.js\",
	\"sassFile\": \"resources/assets/styles/patterns/$1.scss\"
}" > resources/views/patterns/$1/$1.json

echo '{{--
	This element expects:

	And optionally:
--}}' > resources/views/patterns/$1/$1.blade.php

touch resources/assets/styles/patterns/$1.scss

code cypress/integration/$1.spec.js