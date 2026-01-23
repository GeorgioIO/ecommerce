const notificationIcon = `                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24">
                        <path d="M20 18H4l2-2v-6a6 6 0 0 1 5-5.91V3a1 1 0 0 1 2 0v1.09a5.9 5.9 0 0 1 1.3.4A3.992 3.992 0 0 0 18 10v6Zm-8 4a2 2 0 0 0 2-2h-4a2 2 0 0 0 2 2Zm6-18a2 2 0 1 0 2 2 2 2 0 0 0-2-2Z"/>
                    </svg>`;

export function buildDashboardSkeleton() {
  // Main element that will be returned
  const adminDashboard = document.createElement("div");
  adminDashboard.id = "admin-dashboard";

  // HEADER: Create header
  const dashboardHeader = document.createElement("header");
  dashboardHeader.classList.add("dashboard-header");

  // HEADER: Create header content

  // HEADER: Header intro
  const dashboardHeaderIntro = document.createElement("div");
  dashboardHeaderIntro.classList.add("dashboard-header-intro");

  const dashboardTitle = document.createElement("h1");
  dashboardTitle.classList.add("dashboard-title");
  dashboardTitle.textContent = "Dashboard";

  const dashboardSubtitle = document.createElement("p");
  dashboardSubtitle.classList.add("dashboard-subtitle");

  dashboardHeaderIntro.append(dashboardTitle, dashboardSubtitle);

  // HEADER: Header actions
  const dashboardHeaderAction = document.createElement("div");
  dashboardHeaderAction.classList.add("admin-header-intro");

  const notificationButton = document.createElement("button");
  notificationButton.id = "notification-button";
  notificationButton.innerHTML = notificationIcon;

  dashboardHeaderAction.append(notificationButton);

  dashboardHeader.append(dashboardHeaderIntro, dashboardHeaderAction);
  // CONTENT: Create dashboard main content
  const dashboardContent = document.createElement("section");
  dashboardContent.classList.add("dashboard-content");

  const dashboardKPIsContainer = document.createElement("div");
  dashboardKPIsContainer.classList.add("dashboard-kpis");

  const dashboardMain = document.createElement("div");
  dashboardMain.classList.add("dashboard-main");

  dashboardContent.append(dashboardKPIsContainer, dashboardMain);

  adminDashboard.append(dashboardHeader, dashboardContent);

  return adminDashboard;
}
