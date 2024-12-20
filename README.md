<div align="center">
  <br />
    <a href="https://wordpress.org/plugins/pageflash/" target="_blank">
      <img src="https://github.com/theaminuli/pageflash/blob/development/assets/images/screenshot.png" alt="PageFlash Banner">
    </a>
  <br />

  <div>
    <img src="https://img.shields.io/badge/Wordpress-21759B?style=for-the-badge&logo=wordpress&logoColor=white" alt="Wordpress" />
    <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="Javascript" />
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="php" />
    <img src="https://img.shields.io/badge/sponsor-30363D?style=for-the-badge&logo=GitHub-Sponsors&logoColor=#white" alt="Sponsors" />
    <img alt="GitHub License" src="https://img.shields.io/github/license/theaminuli/pageflash?style=for-the-badge&logo=License-GPLv3-blue" alt="License-GPLv3">

  </div>

  <h2 align="center">PageFlash - Fast and Efficient Headless Browser WordPress Plugin</h2>

   <div align="left">
      <h3> By using PageFlash, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading. ⚡️ Boost your website's speed, increase user engagement 💬, and supercharge your online presence 🚀. - NewEgg </h3>
    </div>
</div>

## 📋 <a name="table">Table of Contents</a>

1. 🤖 [Overview](#overview)
2. </> [Tech Stack](#tech-stack)
3. 👀 [Features](#features)
3. 🛠️ [How it works](#works)
4. 🎯 [Why Choose the Name "PageFlash"?](#choose)
4.  🌐 [Browser support](#browser)
4.  ⬇️ [Installation](#installation)
4. 🤸 [Usage](#usage)
5. 📜 [License](#license)
6. ✍ [Contributing](#contributing)
7. ☎️ [Support](#support)
7. 🪧 [Acknowledgments](#acknowledgments)

## <a name="overview">🤖 Overview</a>

**PageFlash** is a powerful headless browser WordPress plugin designed to provide you with a fast and efficient web browsing experience within your WordPress site. Say goodbye to page reloads and enjoy seamless navigation through web content with this plugin. Harness the speed and agility of PageFlash for your WordPress website.

## <a name="tech-stack"> </> Tech Stack</a>

- Javascript
- PHP
- Wordpress
- Quicklink.js

## <a name="features"> 👀 Key Features & Why Choose PageFlash? </a> 

### 👉 Lightning Fast Browsing:
PageFlash lives up to its name, offering rapid page loading and navigation without the need for tedious page refreshes.

### 👉 Smooth Script Execution: 
Execute scripts and interact with web pages in a fluid and continuous manner. With PageFlash, you'll experience uninterrupted script execution, ensuring your web applications run seamlessly.
### 👉 Prefetches:
PageFlash incorporates advanced prefetching technology to speed up your web browsing. It anticipates and loads pages in the background, reducing loading times and providing a smoother browsing experience.

### 👉 No More Reloads:
Say goodbye to unnecessary page reloads with PageFlash, and enjoy uninterrupted web exploration. PageFlash ensures a frustration-free web experience by eliminating the need for page reloads, providing you with a streamlined and seamless browsing experience.

## <a name="works" > 🛠️ How it works </a>

* **Detects links within the viewport** (using [Intersection Observer](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API))
* **Waits until the browser is idle** (using [requestIdleCallback](https://developer.mozilla.org/en-US/docs/Web/API/Window/requestIdleCallback))
* **Checks if the user isn't on a slow connection** (using `navigator.connection.effectiveType`) or has data-saver enabled (using `navigator.connection.saveData`)
* **Prefetches URLs to the links** (using [`<link rel=prefetch>`](https://www.w3.org/TR/resource-hints/#prefetch) or XHR). Provides some control over the request priority (can switch to `fetch()` if supported).


## <a name="choose"> 🎯 Why Choose the Name "PageFlash"? </a>

At PageFlash, we understand the importance of selecting the right name for our WordPress plugin. "PageFlash" was chosen with a purpose, and here's why:

- **Speed and Efficiency:** The name "PageFlash" captures the essence of our plugin's core features – speed and efficiency. When you use PageFlash, you experience lightning-fast web browsing without interruptions. The "quick" signifies the swift page loading, while "flash" implies the instant, seamless transition between web pages.

- **Memorability:** We wanted a name that would be easy to remember. "PageFlash" is concise and sticks in your mind, making it effortless for users to recall when seeking a solution for efficient web browsing.

- **Visual Impact:** "Flash" creates visual imagery of rapid actions, aligning perfectly with the concept of a headless browser smoothly navigating the web. It conveys the idea of quick, responsive, and dynamic web interactions.

- **Positive Connotations:** Both "quick" and "flash" have positive connotations associated with speed and efficiency. These qualities are at the core of our plugin, ensuring that users enjoy a smooth, frustration-free web experience.

By choosing "PageFlash" as our plugin's name, we aim to convey the message that when you use PageFlash, you're choosing a high-speed, efficient, and memorable solution for your headless browser needs.

## <a name="browser"> 🌐 Browser support </a>

This plugin also works perfectly on popular browsers.

- 🖥 Microsoft EDGE
- 🖥 Firefox 4+
- 🖥 Chrome
- 🖥 Opera
- 📱 Android 4+

## <a name="installation"> ⬇️ Installation </a>

To install **PageFlash** on your WordPress site, follow these simple steps:

### To install the plugin via WordPress Dashboard:

1. In your WordPress admin dashboard, go to "Plugins" and click "Add New."

2. Click "Activate."

### To install the plugin manually:

1. Download the plugin ZIP file from the [PageFlash WordPress Plugin Page](https://wordpress.org/plugins/pageflash/).

2. Click the "Upload Plugin" button and select the ZIP file you downloaded.

3. Click "Install Now" and then "Activate."

## <a name="usage"> 🤸 Usage </a>

After activation, PageFlash seamlessly integrates with your WordPress site. To use it, follow these steps:

1. Go to the post or page where you want to enable PageFlash's headless browsing features.

2. In the editor, look for the PageFlash settings panel to configure your preferences.

3. Save your post or page, and PageFlash will handle the rest, providing fast and efficient web navigation.

For more detailed instructions and customization options, check out our [documentation](https://theaminul.com/pageflash/docs).

## <a name="license"> 📜 License </a>

PageFlash is open-source software released under the [GNU General Public License (GPL)](https://www.gnu.org/licenses/gpl-3.0.en.html).

## <a name="contributing"> ✍ Contributing </a>

We welcome contributions from the community! If you'd like to help improve PageFlash, please read our [contribution guidelines](CONTRIBUTING.md) to get started.

## <a name="support"> ☎️ Support </a>

If you encounter issues or have questions, please [open an issue](https://github.com/theaminuli/pageflash/issues). We're here to help!


## <a name="acknowledgments"> 🪧 Acknowledgments </a>

PageFlash wouldn't be possible without the amazing open-source community and the contributions from developers worldwide.

Thank you for choosing PageFlash for your headless browsing needs within your WordPress site!

Happy Browsing! 🚀
