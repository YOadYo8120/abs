# File Upload / Resources Feature

## Overview
Teachers can now upload PDF files to share with their students. This feature mirrors the announcements system, allowing teachers to target specific modules or classes.

## Features Implemented

### Database
- **Migration**: `2025_12_14_113312_create_resources_table.php`
  - Stores file metadata: title, description, file_name, file_path, file_type, file_size
  - Scope-based targeting: module or class
  - Targeting fields: module_id, year, specialization, track, semester
  - Published timestamp for tracking

### Backend

#### Model: `app/Models/Resource.php`
- **Relationships**:
  - `belongsTo(User)` - author of the resource
  - `belongsTo(Module)` - associated module
- **Scopes**:
  - `published()` - filters only published resources
  - `forStudent(Student $student)` - complex filtering based on student's modules and class
- **Filtering Logic**: Mirrors `Announcement::forStudent()` exactly
  - Filters by module schedules the student is enrolled in
  - Matches year, specialization, and track for class-level resources

#### Controller: `app/Http/Controllers/Teacher/ResourceController.php`
- **Methods**:
  - `index()` - Lists teacher's uploaded resources
  - `create()` - Shows upload form with teacher's modules and classes
  - `store()` - Handles file upload with validation
  - `download()` - Serves files for download (both teacher and student)
  - `destroy()` - Deletes file from storage and database
  
- **File Validation**:
  - File type: PDF only (mimes:pdf)
  - Max size: 10MB (max:10240)
  - Required fields: title, file, scope
  
- **Storage**:
  - Disk: public
  - Path: resources/{filename}
  - Files stored in: `storage/app/public/resources/`

- **Security**:
  - Teachers can only upload to modules they teach
  - Verifies module ownership before storing

#### Routes: `routes/web.php`
**Teacher Routes**:
- `GET /teacher/resources` - List resources
- `GET /teacher/resources/create` - Upload form
- `POST /teacher/resources` - Store file
- `GET /teacher/resources/{resource}/download` - Download file
- `DELETE /teacher/resources/{resource}` - Delete file

**Student Routes**:
- `GET /student/resources/{resource}/download` - Download file

### Frontend

#### Teacher Pages

**Upload Form**: `resources/js/pages/Teacher/Resources/Create.vue`
- Title input (required)
- Description textarea (optional)
- File input (PDF only, shows selected file name and size)
- Scope selection: Module or Class
- Module dropdown (when scope is module)
- Class filters: Year, Specialization, Track, Semester (when scope is class)
- Real-time validation feedback
- Success/error messages

**Resources List**: `resources/js/pages/Teacher/Resources/Index.vue`
- Table displaying:
  - Title
  - Scope (Module name or "Class Resource")
  - File name
  - File size (in KB)
  - Upload date
  - Actions: Download and Delete buttons
- Empty state when no resources
- Delete confirmation

**Dashboard Card**: `resources/js/pages/Teacher/Dashboard.vue`
- "Share Resources" card with Upload icon
- Clickable to navigate to `/teacher/resources/create`
- Green gradient styling

#### Student Pages

**Dashboard Display**: `resources/js/pages/Student/Dashboard.vue`
- "Shared Resources" card displaying up to 10 recent resources
- For each resource:
  - Title and description
  - Scope badge (module name or "Class Resource")
  - File icon with filename
  - File size in KB
  - Author name
  - Published date
  - Download button with arrow icon
- Styled similar to announcements section
- Only shows resources the student has access to

## Data Flow

### Upload Flow
1. Teacher navigates to "Share Resources" from dashboard
2. Fills upload form with file and metadata
3. Form validates: PDF only, 10MB max
4. File stored in `storage/app/public/resources/`
5. Record created in database with file path
6. Teacher redirected to resources list

### Student View Flow
1. Student logs in and views dashboard
2. Backend fetches resources using `Resource::forStudent($student)`
3. Filters resources by:
   - Student's enrolled modules
   - Student's class (year, specialization, track)
   - Published status
4. Maps to frontend format with file size in KB
5. Student sees resources in dashboard
6. Click download button → file served from storage

## File Storage Structure
```
storage/
  app/
    public/
      resources/
        {unique-filename}.pdf
        {unique-filename}.pdf
        ...
```

## Testing Checklist

### Teacher
- [ ] Navigate to Teacher Dashboard
- [ ] Click "Share Resources" card
- [ ] Upload a PDF file (< 10MB)
- [ ] Try uploading non-PDF (should fail)
- [ ] Try uploading file > 10MB (should fail)
- [ ] Select Module scope and choose a module
- [ ] Select Class scope and set year/specialization
- [ ] Submit and verify redirect to list
- [ ] View uploaded resource in list
- [ ] Download the file
- [ ] Delete the resource
- [ ] Verify file deleted from storage

### Student
- [ ] Login as student
- [ ] View dashboard
- [ ] Verify "Shared Resources" section appears
- [ ] See resources from teacher's modules
- [ ] Click download button
- [ ] Verify file downloads correctly
- [ ] Check file opens as PDF

### Edge Cases
- [ ] Teacher uploads to module they don't teach (should fail)
- [ ] Student tries to download resource they don't have access to
- [ ] File with special characters in name
- [ ] Large file near 10MB limit
- [ ] Multiple files with same name (should generate unique names)

## Technical Notes

### Storage Link
Ensure storage is linked for public access:
```bash
php artisan storage:link
```

### Permissions
Verify storage directory permissions:
```bash
chmod -R 775 storage/app/public
```

### File Naming
- Files stored with unique names using `store()` method
- Original filename preserved in `file_name` column
- Prevents overwriting files with same name

### Filtering Logic
The `Resource::forStudent()` method uses the exact same logic as `Announcement::forStudent()`:
- Module scope: Checks if student has schedules for that module
- Class scope: Matches year, specialization, and track
- Only published resources shown
- Eager loads user and module relationships for efficiency

## Future Enhancements
- Support more file types (Word, Excel, images)
- File preview functionality
- Download statistics
- Student feedback/comments
- Bulk file upload
- File versioning
- Expiration dates for resources
- Resource categories/tags
