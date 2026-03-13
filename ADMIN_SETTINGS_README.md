# Admin Settings System Documentation

## Overview
The admin settings system allows administrators to customize all aspects of the website appearance and content through an intuitive web interface. Settings are stored in JSON files and automatically reflected across all frontend pages.

## Features

### 1. General Settings
- **Site Name**: Main website title displayed in header and browser tab
- **Site Description**: Meta description for SEO
- **Site Email**: Contact email address
- **Site Phone**: Contact phone number
- **WhatsApp Number**: WhatsApp business number for quick contact
- **Site Logo**: Upload custom logo image
- **Site Favicon**: Upload custom favicon

### 2. Homepage Settings
- **Hero Title**: Main headline on homepage
- **Hero Subtitle**: Supporting text under main headline
- **Hero Description**: Detailed description text
- **Hero Background Image**: Background image for hero section
- **Hero Button Text**: Call-to-action button text
- **Hero Button URL**: Link destination for CTA button

### 3. About Page Settings
- **About Title**: Page title
- **About Content**: Main content text
- **About Image**: Featured image
- **Mission Statement**: Organization mission
- **Vision Statement**: Organization vision

### 4. Contact Page Settings
- **Contact Title**: Page title
- **Contact Description**: Page description
- **Contact Address**: Physical address
- **Contact Map Embed**: Google Maps embed code
- **Contact Hours**: Business hours

### 5. Partnership Settings
- **Partnership Title**: Page title
- **Partnership Description**: Page description
- **Partnership Content**: Detailed partnership information

## Technical Implementation

### File Structure
```
app/Http/Controllers/Admin/SettingController.php  # Main controller
app/helpers.php                                   # Helper functions
storage/app/settings.json                         # Settings storage
resources/views/admin/settings/index.blade.php    # Admin interface
```

### Helper Functions
```php
// Get single setting with fallback
get_setting('key', 'default_value')

// Get all settings as array
get_settings()
```

### Usage in Templates
```blade
{{-- Get site name --}}
{{ get_setting('site_name', 'Default Site Name') }}

{{-- Get hero title --}}
{{ get_setting('hero_title', 'Welcome to Our Site') }}

{{-- Check if setting exists --}}
@if(get_setting('hero_background_image'))
    <img src="{{ asset('storage/' . get_setting('hero_background_image')) }}" alt="Hero">
@endif
```

## Admin Interface Features

### Modern UI Design
- Card-based layout with gradient headers
- Organized sections with icons
- Responsive design for all screen sizes
- Real-time image preview for uploads

### JavaScript Enhancements
- Image preview before upload
- Form validation for required fields
- Auto-formatting for phone numbers
- Loading states during save operations

### File Upload Handling
- Supports JPG, PNG, GIF formats
- Automatic file naming with timestamps
- Storage in `storage/app/public/uploads/`
- Secure file validation

## Security Features
- Admin middleware protection
- CSRF protection on forms
- File type validation
- Input sanitization

## Integration Points
The settings system is integrated across all frontend pages:
- `resources/views/home.blade.php`
- `resources/views/about.blade.php`
- `resources/views/contact.blade.php`
- `resources/views/partnership.blade.php`
- `resources/views/layouts/app.blade.php`

## Future Enhancements
- Multiple language support
- Theme customization options
- Advanced image editing tools
- Settings backup/restore functionality
- Audit logging for changes

## Troubleshooting

### Settings Not Updating
1. Check file permissions on `storage/app/settings.json`
2. Verify admin has write permissions
3. Clear application cache: `php artisan cache:clear`

### Images Not Displaying
1. Run storage link: `php artisan storage:link`
2. Check file permissions on `storage/app/public/uploads/`
3. Verify uploaded files exist

### Form Validation Errors
1. Ensure all required fields are filled
2. Check file upload size limits
3. Verify supported file formats

## API Endpoints
- `GET /admin/settings` - Display settings form
- `POST /admin/settings` - Update settings
- `GET /api/settings/{key}` - Get specific setting (future feature)