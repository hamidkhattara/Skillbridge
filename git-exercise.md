# Git & GitHub Exercise: SkillBridge Project

This exercise will test your knowledge of common Git and GitHub workflows, including branches, commits, pull requests, and resolving merge conflicts.

**Instructions:** Follow the steps below. Do not ask me for the specific commands to use.

---

### Part 1: Create a Feature Branch and a Pull Request

1.  **Start Fresh:** Make sure you are on your main branch (`main` or `master`) and that your local repository is up-to-date with the remote.

2.  **Create a Feature Branch:** Create a new branch from your main branch. Name it `feature/add-project-description`.

3.  **Modify the README:**
    *   On the `feature/add-project-description` branch, open the `README.md` file.
    *   At the end of the file, add a new section with the following content:
        ```markdown
        ## Project Description

        SkillBridge is a platform designed to connect skilled professionals with those seeking their services.
        ``` 
    *   Save the file.

4.  **Commit and Push:**
    *   Stage and commit your changes with a clear commit message (e.g., "feat: Add project description to README").
    *   Push the `feature/add-project-description` branch to your remote repository on GitHub.

5.  **Open a Pull Request:**
    *   Go to your project's repository on GitHub.
    *   Open a new pull request to merge the `feature/add-project-description` branch into your main branch.
    *   Give it a title like "Feature: Add Project Description".
    *   **Do not merge it yet!**

---

### Part 2: Create a Merge Conflict

1.  **Switch Branches:** Switch back to your main branch locally.

2.  **Create a Conflicting Change:**
    *   On your main branch, open the same `README.md` file.
    *   At the end of the file, add a different section:
        ```markdown
        ## About SkillBridge

        This project is an application for booking sessions with coaches.
        ```
    *   Save the file.

3.  **Commit and Push:**
    *   Stage and commit this change directly to your main branch with a message like "docs: Add about section to README".
    *   Push this commit to your remote main branch.

---

### Part 3: Resolve the Conflict

1.  **Identify the Conflict:** Go back to the pull request you opened on GitHub. You should now see a message indicating that there is a merge conflict.

2.  **Update Your Feature Branch:** Your `feature/add-project-description` branch is now out of date with the main branch. Pull the latest changes from the remote main branch into your local feature branch. This action will trigger a merge conflict on your local machine.

3.  **Resolve the Conflict Locally:**
    *   Open the `README.md` file in your editor. You will see the conflict markers (`<<<<<<<`, `=======`, `>>>>>>>`).
    *   Edit the file to resolve the conflict. Combine the two sections into one so that the end of the file looks like this:
        ```markdown
        ## About SkillBridge

        SkillBridge is a platform designed to connect skilled professionals with those seeking their services. This application allows for booking sessions with coaches.
        ```
    *   Save the file.

4.  **Finalize the Merge:**
    *   Stage the resolved `README.md` file.
    *   Complete the merge by creating a new commit. Your editor or Git tool will likely pre-populate a commit message for you (e.g., "Merge branch 'main' into feature/add-project-description").

5.  **Push the Resolution:** Push your changes (including the merge commit) to the remote `feature/add-project-description` branch.

6.  **Merge the Pull Request:**
    *   Return to your pull request on GitHub. The conflict should now be resolved.
    *   Merge the pull request.
    *   You can now safely delete the `feature/add-project-description` branch.

---

Good luck!
