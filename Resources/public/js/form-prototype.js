/**
 * Inserts link onto stage to add additional rows for Multiple relationships
 */

window.addEvent('domready', function() {
	// Taking all elements that have the data-prototype attribute
	$$('div[data-prototype]').each(function(element) {

		var prototype = element.get('data-prototype'),
		    new_prototype_link = new Element('a', {
		    href: '#',
		    'class': 'new-prototype',
		    html: 'Add New'
		});

		// Click event to inject new row of data provided by prototype
		new_prototype_link.addEvent('click', function(event) {
			event.stop();

			// Format variables for display
			var collection_children = element.getChildren().length,
			    updated_prototype = new Element('div', {
			    	html: prototype.replace(/\$\$name\$\$/g, collection_children)
			    }).getFirst();

			updated_prototype.inject(new_prototype_link, 'before');
		});

		// Insert 'Add New' link on stage
		element.grab(new_prototype_link);
	});
});
