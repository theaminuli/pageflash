

name: Bug report

description: Report a bug with the PageFlash WordPress plugin

labels: ['[Type] Bug']

body:
    - type: markdown
      attributes:
          value: |
              Thank you for taking the time to report a bug in the PageFlash plugin! If this is a security issue, please report it privately at https://github.com/theaminuli/pageflash/security.

    - type: textarea
      attributes:
          label: Description
          description: Please provide a detailed description of the bug, including what you expected to happen and what is currently happening.
          placeholder: |
              Feature '...' is not working properly. I expect '...' to happen, but '...' happens instead.
      validations:
          required: true

    - type: textarea
      attributes:
          label: Step-by-step reproduction instructions
          description: Describe the exact steps to reproduce the issue. This will help us debug the problem more effectively.
          placeholder: |
              1. Go to '...'
              2. Click on '...'
              3. Scroll down to '...'
      validations:
          required: true

    - type: textarea
      attributes:
          label: Screenshots, screen recording, or code snippet
          description: |
              Please upload a screenshot, screen recording, or relevant code snippet to demonstrate the bug. You can use tools like LICEcap (https://www.cockos.com/licecap/) to create a GIF recording. 
              Tip: Drag and drop files here to upload. For larger code samples, consider sharing them via GitHub Gist (https://gist.github.com).
          validations:
              required: false

    - type: textarea
      attributes:
          label: Environment info
          description: |
              Provide details about your environment, including WordPress version, PageFlash version, and other relevant information.
          placeholder: |
              - WordPress version
              - PageFlash version
              - Active theme
              - Browser(s) tested on
              - Device and operating system
      validations:
          required: false

    - type: checkboxes
      id: existing
      attributes:
          label: Please confirm you have searched existing issues in the repository.
          description: You can search at https://github.com/theaminuli/pageflash/issues to ensure the bug has not already been reported.
          options:
              - label: 'Yes'
                required: true

    - type: checkboxes
      id: plugins
      attributes:
          label: Please confirm you have tested with other plugins deactivated.
          description: Ensure the issue is not caused by a conflict with other plugins.
          options:
              - label: 'Yes'
                required: true

    - type: checkboxes
      id: themes
      attributes:
          label: Please confirm which theme type you used during testing.
          options:
              - label: 'Block'
              - label: 'Classic'
              - label: 'Hybrid (e.g., classic with theme.json)'
              - label: 'Not sure'
