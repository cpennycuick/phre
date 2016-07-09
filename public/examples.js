function renderExamples (data) {
	var referenceParts = [];
	var contentParts = [];

	var examplesRoot = 'examples';
	var hashRoot = 'Examples';

	var entry, hash, url;
	for (var i = 0; i < data.length; i++) {
		entry = data[i];

		hash = hashRoot+'/'+entry.Path;
		url = examplesRoot+'/'+entry.Path+'/example.';

		referenceParts.push('<li><a href="#'+hash+'">'+entry.Name+'</a></li>')
		contentParts = contentParts.concat([
			'<h2><a name="'+hash+'"></a>'+entry.Name+'</h2>',
			'<div>',
				([
					'<a href="https://github.com/cpennycuick/phre/tree/gh-pages/examples/'+entry.Path+'/" target=_blank">GitHub</a>',
					'<a href="'+url+'html" target="_blank">HTML</a>',
					'<a href="'+url+'pdf" target="_blank">PDF</a>'
				]).join(' / '),
			'</div>',
			'<p>'+entry.Description+'</p>'
		]);
	}

	var content = '<ul>'+referenceParts.join('')+'</ul>'+contentParts.join('');

	window.onload.add(function() {
		document.getElementById('ExamplesList').innerHTML = content;
	});

}
