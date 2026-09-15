Update the hero section layout based on the attached screenshot.

### 1. Increase the right-side image size

* Enlarge the person and SaaS dashboard visual on the right side.
* Make it as large as possible while keeping the complete visual inside the red-bordered hero container.
* The image must not extend beyond the top or bottom edges of the red border.
* Preserve the original aspect ratio. Do not stretch, distort, or crop important parts of the image.
* Use the available right-side space efficiently, allowing the image to extend close to the bottom border without overflowing.

### 2. Prevent overlap with left-side content

* The left-side heading, paragraph, CTA buttons, and feature labels must remain fully visible.
* The enlarged right-side image must never overlap, cover, or hide any left-side text or buttons.
* Allocate a dedicated left column for the text and a dedicated right column for the visual.
* The left column must have enough width for the heading and paragraph to wrap naturally.
* The right image must remain within its own column, even when enlarged.

### 3. Correct container and image positioning

* Keep the red border as the exact boundary of the hero section.
* Set the hero container to `position: relative` and `overflow: hidden`.
* Use a responsive two-column grid or flexbox layout.
* Set the image to `width: 100%; height: 100%; object-fit: contain;` within its allocated visual area.
* Adjust the image's maximum width and height to maximize its size without exceeding the container.
* Do not use excessive absolute positioning or negative margins that cause overflow.
* Ensure the image's top and bottom edges stay within the red border.

### 4. Responsive behavior

* On desktop, maintain a two-column layout with the enlarged image on the right.
* On tablet and mobile, stack the image below the left-side content.
* Ensure no text, buttons, or visual elements overlap at any screen width.
* Maintain appropriate spacing and readability.

### 5. Preserve the original design

* Keep the existing typography, colors, buttons, feature labels, and overall visual style.
* Do not remove or hide any existing content.
* Do not change the red border's intended position or dimensions.

**Final objective:** Create a balanced hero section where the right-side person and SaaS dashboard image is significantly larger, fully contained within the red border, and never overlaps the left-side content. The complete image should remain visible and the layout should look polished and professional.
