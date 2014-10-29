/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarpis.net;

import org.json.JSONObject;

/**
 * Listener : execute action after the task
 * @author Jonathan
 *
 */
public interface IPostExecuteListener {
	public void execute(JSONObject response);
}
