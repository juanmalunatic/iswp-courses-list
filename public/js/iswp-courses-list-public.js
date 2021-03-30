(function( $ ) {
	'use strict';
	window.iswpcl_fired = 0;
	$(function() {
		if (window.iswpcl_fired === 0) {
			window.iswpcl_fired = 1;
			iswpCoursesListSetup();
		}
	});
})( jQuery );

function iswpCoursesListSetup() {

	const courses = window.iswpcl_json;

	// Initialize select boxes
	initializeCategories(courses);
	initializeLanguages(courses);

	// Trigger the initial draw
	redrawCards(courses);

}

function initializeCategories (courses) {
	let categories = ['All categories'];
	console.log(categories);

	categories = categories.concat(extractCategories(courses));
	console.log(categories);

	let selector = document.getElementById('iswpcl-selector-category');
	for (const category of categories) {
		let option = document.createElement("option");
		option.value = category;
		option.text = category;
		selector.add(option);
	}

	selector.addEventListener('change', (event) => {
		redrawCards(courses);
	});
}

function initializeLanguages (courses) {
	let languages = ['All languages'];
	languages = languages.concat(extractLanguages(courses));

	let selector = document.getElementById('iswpcl-selector-language');
	for (const language of languages) {
		let option = document.createElement('option');
		option.value = language;
		option.text = language;
		selector.add(option);
	}

	selector.addEventListener('change', (event) => {
		redrawCards(courses);
	})
}

function redrawCards (courses) {
	// Get data
	const filtered_courses = filterCourses(courses);
	const card_holder = document.getElementById('iswpcl-cards');

	// Destroy stage
	card_holder.innerHTML = '';

	// Recreate stage
	for (const course of filtered_courses) {
		let new_card = createCard(course);
		card_holder.appendChild(new_card);
	}
}

function createCard(card_data) {

	const renderedCard = renderCard(card_data);

	let newDiv = document.createElement('div');
	newDiv.className = 'iswpcl_card_holder';
	newDiv.innerHTML = renderedCard;

	return newDiv;
}

function renderCard(card_data) {

	const title = card_data['title'];
	const description =	card_data['description'] ? card_data['description'] : '';
	const course_url = card_data['link'];
	const image_url = `url(${card_data['image']}`;

	const template = `
	<div class="iswpcl_card">
		<a class="iswpcl_card_link" href="${course_url}">
			<div class="iswpcl_card_image"
				 style="background-image: ${image_url }) ">	
			</div>
			<div class="iswpcl_card_texts">
				<div class="iswpcl_card_title">
					${title}
				</div>
				<div class="iswpcl_card_description">
					${description}
				</div>
			</div>		
		</a>
	</div>
	`;

	return template;
}

function filterCourses (courses) {

	// Fetch filter values
	let sel_language = document.getElementById('iswpcl-selector-language');
	let sel_category = document.getElementById('iswpcl-selector-category');

	const filter_language = sel_language.value;
	const filter_category = sel_category.value;

	let courses_filtered = courses;

	// First, filter by language
	if (filter_language !== 'All languages') {
		courses_filtered = courses_filtered.filter(
			item => item.language === filter_language
		);
	}

	// Then, filter by category
	if (filter_category !== 'All categories') {
		courses_filtered = courses_filtered.filter(
			item => item.categories.includes(filter_category)
		)
	}

	return courses_filtered;
}

function extractLanguages (courses) {
	return [...new Set(
		courses
			.filter( item => item.language !== false ) // Remove items w/o language
			.map( item => item.language )              // Grab language values
	)];
}

function extractCategories (courses) {
	// Extract cats
	let course_cats = courses.map(item => item.categories);
	// Create a single array w/ duplicates
	let cats_flat = course_cats.flat();
	// Convert to a set (remove dupes) and convert it to array
	return [... new Set(cats_flat)];
}