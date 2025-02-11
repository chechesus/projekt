<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Article Editor</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .roll-down {
      display: none;
      margin-top: 10px;
    }
    .roll-down.active {
      display: block;
    }
    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    input[type="text"], textarea, select {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      padding: 10px 15px;
      font-size: 14px;
      border: none;
      background-color: #4CAF50;
      color: white;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #45a049;
    }
    #editor {
      border: 1px solid #ccc;
      padding: 10px;
      min-height: 300px;
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .element {
      margin: 10px 0;
      padding: 10px;
      border: 1px dashed #aaa;
      background-color: #f9f9f9;
      cursor: grab;
    }

    .element.dragging {
      opacity: 0.5;
    }
  </style>
</head>
<body>
  <h1>Article Editor</h1>

  <!-- Editor for Drag-and-Drop Elements -->
  <div id="editor" ondragover="allowDrop(event)" ondrop="drop(event)"></div>

  <!-- Options to Add Elements -->
  <select id="elementType" onchange="showForm()">
    <option value="">-- Select Element Type --</option>
    <option value="paragraph">Paragraph</option>
    <option value="poll">Poll</option>
    <option value="image">Image</option>
  </select>

  <!-- Forms for Adding Elements -->
  <div id="forms">
    <div id="paragraphForm" style="display: none;">
      <textarea id="paragraphContent" placeholder="Write paragraph text..."></textarea>
      <button onclick="addParagraph()">Add Paragraph</button>
    </div>

    <div id="pollForm" style="display: none;">
      <input type="text" id="pollQuestion" placeholder="Enter poll question">
      <div id="pollOptions"></div>
      <button onclick="addPollOption()">Add Option</button>
      <button onclick="addPoll()">Add Poll</button>
    </div>

    <div id="imageForm" style="display: none;">
      <input type="file" id="imageInput" onchange="previewImage(event)">
      <input type="text" id="imageTitle" placeholder="Image title (optional)">
      <button onclick="addImage()">Add Image</button>
    </div>
  </div>

  <!-- Publishing Options -->
  <form id="articleForm">
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" placeholder="Enter article title">
    </div>

    <div class="form-group">
      <label for="publish-options">Publish Options</label>
      <div>
        <button type="button" id="publishNow">Publish Now</button>
        <button type="button" id="schedulePublish">Schedule Publish</button>
      </div>
      <div id="scheduleOptions" class="roll-down">
        <label for="scheduleDate">Schedule Date:</label>
        <input type="date" id="scheduleDate" name="scheduleDate">

        <label for="scheduleTime">Schedule Time:</label>
        <input type="time" id="scheduleTime" name="scheduleTime">
      </div>
    </div>

    <button type="submit">Save Article</button>
  </form>

  <script>
    // Show appropriate form for adding an element
    function showForm() {
      document.querySelectorAll('#forms > div').forEach(form => form.style.display = 'none');
      const selected = document.getElementById('elementType').value;
      if (selected) document.getElementById(selected + 'Form').style.display = 'block';
    }

    // Add paragraph
    function addParagraph() {
      const content = document.getElementById('paragraphContent').value;
      if (!content.trim()) return alert("Paragraph text cannot be empty!");

      const paragraph = createDraggableElement();
      paragraph.textContent = content;

      document.getElementById('editor').appendChild(paragraph);
      document.getElementById('paragraphContent').value = '';
    }

    // Add poll
    function addPollOption() {
      const pollOptions = document.getElementById('pollOptions');
      const optionInput = document.createElement('input');
      optionInput.type = 'text';
      optionInput.placeholder = 'Enter poll option';
      pollOptions.appendChild(optionInput);
    }

    function addPoll() {
      const question = document.getElementById('pollQuestion').value;
      const pollOptions = document.getElementById('pollOptions').querySelectorAll('input');

      if (!question.trim()) return alert("Poll question cannot be empty!");
      if (pollOptions.length === 0) return alert("Add at least one option!");

      const poll = createDraggableElement();

      const pollQuestion = document.createElement('h3');
      pollQuestion.textContent = question;
      poll.appendChild(pollQuestion);

      pollOptions.forEach(option => {
        if (option.value.trim()) {
          const label = document.createElement('label');
          label.textContent = option.value;
          const input = document.createElement('input');
          input.type = 'checkbox';
          label.prepend(input);
          poll.appendChild(label);
          poll.appendChild(document.createElement('br'));
        }
      });

      document.getElementById('editor').appendChild(poll);

      document.getElementById('pollQuestion').value = '';
      document.getElementById('pollOptions').innerHTML = '';
    }

    // Preview and add image
    let imagePreviewSrc = '';
    function previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreviewSrc = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    }

    function addImage() {
      if (!imagePreviewSrc) return alert("Select an image!");
      const title = document.getElementById('imageTitle').value;

      const imageWrapper = createDraggableElement();

      const img = document.createElement('img');
      img.src = imagePreviewSrc;

      const imgTitle = document.createElement('strong');
      imgTitle.textContent = title;

      imageWrapper.appendChild(img);
      if (title.trim()) imageWrapper.appendChild(imgTitle);

      document.getElementById('editor').appendChild(imageWrapper);

      document.getElementById('imageTitle').value = '';
      imagePreviewSrc = '';
    }

    // Create draggable element
    function createDraggableElement() {
      const element = document.createElement('div');
      element.className = 'element';
      element.draggable = true;
      element.ondragstart = drag;
      element.ondragend = () => element.classList.remove('dragging');
      return element;
    }

    // Drag-and-drop functionality
    function allowDrop(ev) {
      ev.preventDefault();
      const dragging = document.querySelector('.dragging');
      const elements = [...document.getElementById('editor').children];
      const afterElement = elements.find(child => {
        const box = child.getBoundingClientRect();
        return ev.clientY < box.top + box.height / 2;
      });
      if (afterElement) {
        document.getElementById('editor').insertBefore(dragging, afterElement);
      } else {
        document.getElementById('editor').appendChild(dragging);
      }
    }

    function drag(ev) {
      ev.target.classList.add('dragging');
      ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev) {
      ev.preventDefault();
      const dragging = document.querySelector('.dragging');
      dragging.classList.remove('dragging');
    }

    // Toggle schedule options
    document.getElementById('schedulePublish').addEventListener('click', function() {
      const scheduleOptions = document.getElementById('scheduleOptions');
      scheduleOptions.classList.toggle('active');
    });

    // Publish now
    document.getElementById('publishNow').addEventListener('click', function() {
      alert('The article will be published immediately.');
    });

    // Save article
    document.getElementById('articleForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const title = document.getElementById('title').value;
      const scheduleDate = document.getElementById('scheduleDate').value;
      const scheduleTime = document.getElementById('scheduleTime').value;

      if (!title.trim()) {
        alert('Please fill out the title.');
        return;
      }

      if (scheduleDate && scheduleTime) {
        alert(`The article is scheduled to be published on ${scheduleDate} at ${scheduleTime}.`);
      } else {
        alert('The article has been saved successfully.');
      }
    });
  </script>
</body>
</html>
