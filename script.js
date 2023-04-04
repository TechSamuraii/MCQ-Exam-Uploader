const form = document.getElementById('upload-form');
const message = document.getElementById('message');
const fileInput = document.getElementById('csv-file');

form.addEventListener('submit', (e) => {
	e.preventDefault();

	const question = document.getElementById('question').value;
	const option1 = document.getElementById('option1').value;
	const option2 = document.getElementById('option2').value;
	const option3 = document.getElementById('option3').value;
	const option4 = document.getElementById('option4').value;
	const answer = document.getElementById('answer').value;
	const image = document.getElementById('image').files[0];

	const formData = new FormData();
	formData.append('question', question);
	formData.append('option1', option1);
	formData.append('option2', option2);
	formData.append('option3', option3);
	formData.append('option4', option4);
	formData.append('answer', answer);
	formData.append('image', image);

	fetch('upload.php', {
		method: 'POST',
		body: formData
	})
	.then(response => {
		if (response.ok) {
			message.textContent = 'Question uploaded successfully.';
			form.reset();
		} else {
			message.textContent = 'Error uploading question.';
		}
	})
	.catch(error => {
		message.textContent = 'Error uploading question.';
	});
});

fileInput.addEventListener('change', (e) => {
	const file = e.target.files[0];
	const reader = new FileReader();

	reader.onload = () => {
		const lines = reader.result.split('\n');
		const questions = [];

		for (let i = 1; i < lines.length; i++) {
			const fields = lines[i].split(',');
			const question = {
				question: fields[0],
				option1: fields[1],
				option2: fields[2],
				option3: fields[3],
				option4: fields[4],
				answer: fields[5],
				image: fields[6]
			};
			questions.push(question);
		}

		const formData = new FormData();
		formData.append('questions', JSON.stringify(questions));

		fetch('upload_csv.php', {
			method: 'POST',
			body: formData
		})
		.then(response => {
			if (response.ok) {
				message.textContent = 'Questions uploaded successfully.';
				fileInput.value = '';
			} else {
				message.textContent = 'Error uploading questions.';
			}
		})
		.catch(error => {
			message.textContent = 'Error uploading questions.';
		});
	};

	reader.readAsText(file);
});
