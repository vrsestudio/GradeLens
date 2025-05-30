# ======================
# GradeLens
#### A Tool For Students
# ======================

## Before You Begin
Make sure you read the following:
- [README](https://github.com/vrsestudio/GradeLens/blob/main/README.md)
- [PRIVACY POLICY](https://github.com/vrsestudio/GradeLens/blob/main/PRIVACY.md)
- [SECURITY POLICY](https://github.com/vrsestudio/GradeLens/blob/main/SECURITY.md)
- [CODE OF CONDUCT](https://github.com/vrsestudio/GradeLens/blob/main/CODE_OF_CONDUCT.md)
- [CONTRIBUTING](https://github.com/vrsestudio/GradeLens/blob/main/CONTRIBUTING.md)

GradeLens is published under the [GNU General Public License v3.0](https://github.com/vrsestudio/GradeLens/blob/main/LICENSE)

## Setup Instructions

### Pre Setup Information
If you don't want to set up GradeLens on your local machine, you can contact us to temporarily host it for you. Please note that this is a free service and may not be available at all times.

If you want to set up GradeLens on your local machine, follow the instructions below or check out the [GradeLensInstaller](https://github.com/vrsestudio/GradeLensInstaller).

### Step 1: Install Dependencies
1. Download and install [XAMPP](https://www.apachefriends.org/de/download.html) for your operating system. (Version 8.2.12)

### Step 2: Download GradeLens
1. Download the latest version of GradeLens from the [GitHub repository](https://github.com/vrsestudio/GradeLens/releases/tag/Release)
    - Click on the latest release link.
    - Locate the `Source Code.zip` or `gradelens.zip` file under "Assets".
    - Click to download the ZIP file.
    - Wait for the download to complete.
    - Locate the downloaded ZIP file in your downloads folder or specified download location.
    - Copy the downloaded ZIP file to your XAMPP `htdocs` directory, typically found at `C:\xampp\htdocs\` on Windows or `/opt/lampp/htdocs/` on Linux.
    - Unzip the file in the `htdocs` directory.
2. Clone the repository using a program like GitHub Desktop
    - Open GitHub Desktop.
    - Click on "File" in the menu bar.
    - Select "Clone Repository".
    - In the "Clone a Repository" dialog, enter the repository URL: `https://github.com/vrsestudio/GradeLens`
    - Choose the local path where you want to clone the repository, typically your XAMPP `htdocs` directory.

### Step 3: Configure Setup Database
1. Open your web browser and navigate to `http://localhost/phpmyadmin`.
2. Open your file manager and navigate to the `gradelens` directory in your XAMPP `htdocs`.
3. Locate the `creation.sql` file in the `gradelens/database` directory.
4. Copy the contents of `creation.sql`.
5. In phpMyAdmin, click on the "SQL" tab.
6. Paste the copied SQL code into the SQL query box.
7. Click the "Go" button to execute the SQL query.
8. Wait for the confirmation message indicating that the database has been created successfully.

### Done!
You have successfully set up GradeLens! You can now access it by navigating to `http://localhost/gradelens` or `http://YOURIPADDRESS/gradelens` in your web browser.


##### GradeLens and GradeLensInstaller are published by [vrsestudio](https://github.com/vrsestudio/). Main contributor: [Jonas Fessler](https://github.com/jonasfessler)