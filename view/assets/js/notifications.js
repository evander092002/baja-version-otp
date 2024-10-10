import { user } from '~/assets/js/userLogged';
import Notifications from '~/components/Table/Notifications.vue';
import { reactive } from 'vue';

export const notifications = reactive([]); // Start with an empty array

// Initialize the motion object
const mapNotificationData = (data) => {
  return {
    motion_detected: data.motion.name,
    username: data.user.username,
    description: data.motion.description,
    screenshot: data.screenshots,
    threshold: data.motion.threshold,
    date_captured: data.created_at,
    deleted_at: data.deleted_at
  };
};

export async function fetchNotifications() {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/notifications', {
      method: 'GET',
      headers: {
        Authorization: 'Bearer ' + localStorage.getItem('_token') // Use 'Bearer' prefix for token
      }
    });

    if (!response.ok) {
      throw new Error(`No data found in response: ${response.statusText}`);
    }

    const data = await response.json();
    console.log('Fetched user data:', data);
    
    // Clear existing notifications (if needed)
    notifications.splice(0, notifications.length); 

    // Populate notifications based on fetched data
    data.forEach(item => {
      notifications.push(mapNotificationData(item)); // Use push to add new notifications
    });

  } catch (error) {
    console.error('Error fetching notifications:', error.message);
  }
}
