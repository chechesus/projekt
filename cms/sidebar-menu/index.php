<div class="sidebar-wrapper">

    <header class="sidebar-header">
        <a href="/projekt/index.php" class="header-logo">
            <img src="/projekt/images/logo.jpg">
        </a>
        <button class="toggler sidebar-toggler">
            <span class="material-symbols-rounded">chevron_left</span>
        </button>
        <button class="toggler menu-toggler">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </header>

    <nav class="sidebar-nav">
        <ul class="nav-list primary-nav" id="primaryNav">
            <!-- Dynamicky generované položky -->
        </ul>

        <ul class="nav-list secondary-nav">
            
            <li class="nav-item">
                <a href="/projekt/api/logout.php" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">logout</span>
                    <span class="nav-label">Odhlásiť sa</span>
                </a>
                <span class="nav-tooltip">Logout</span>
            </li>
        </ul>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const primaryNav = document.getElementById('primaryNav');
        const userRole = "<?= $_SESSION['role_id'] ?>"; 
        let navItems = [];

        console.log(userRole);
        switch (userRole) {
            case "1":
                navItems = [{
                        icon: 'dashboard',
                        label: 'Domov',
                        href: '/projekt/cms/admin.php'
                    },
                    {
                        icon: 'calendar_today',
                        label: 'Kalendár',
                        href: '/projekt/cms/admin_funct/google-calendar.php'
                    },
                    {
                        icon: 'format_image_left',
                        label: 'Pridať články',
                        href: '/projekt/cms/article_maker/editor.php'
                    },
                    {
                        icon: 'table_edit',
                        label: 'Galéria',
                        href: '/projekt/cms/admin_funct/save_gallery.php/'
                    },
                    {
                        icon: 'block',
                        label: 'Blokovanie používateľov',
                        href: '/projekt/cms/admin_funct/user_edit/blocking.php'
                    },
                    {
                        icon: 'edit_note',
                        label: 'Úprava používateľov',
                        href: '/projekt/cms/admin_funct/user_edit/edit.php'
                    }
                ];
                break;
            case "3":
                navItems = [{
                        icon: 'dashboard',
                        label: 'Domov',
                        href: '/projekt/cms/moderator_dashboard.php'
                    },
                    {
                        icon: 'comment',
                        label: 'Správa komentárov',
                        href: 'admin_funct/google-calendar.php'
                    },
                    {
                        icon: 'full_coverage',
                        label: 'Správa príspevkov',
                        href: 'admin_funct/google-calendar.php'
                    },
                    {
                        icon: 'block',
                        label: 'Blokovanie používateľov',
                        href: '/projekt/cms/admin_funct/user_edit/blocking.php'
                    },
                ];
                break;
            case "0":
                navItems = [{
                        icon: 'dashboard',
                        label: 'Domov',
                        href: 'moderator_dashboard.php'
                    }

                ];
                break;
            case "2":
                navItems = [{
                        icon: 'dashboard',
                        label: 'Domov',
                        href: 'cms/user_dashboard.php'
                    }

                ];
                break;
            default:
                // Predvolené položky pre neznámu rolu
                navItems = [{
                    icon: 'dashboard',
                    label: 'Domov',
                    href: '#'
                }];
        }

        navItems.forEach(item => {
            const li = document.createElement('li');
            li.className = 'nav-item';
            li.innerHTML = `
                <a href="${item.href}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">${item.icon}</span>
                    <span class="nav-label">${item.label}</span>
                </a>
                <span class="nav-tooltip">${item.label}</span>
            `;
            primaryNav.appendChild(li);
        });
    });
</script>