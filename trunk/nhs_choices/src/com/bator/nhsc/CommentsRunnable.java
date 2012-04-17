package com.bator.nhsc;

import java.util.ArrayList;
import java.util.List;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;

import android.util.Log;

import com.bator.nhsc.net.NetUtil;
import com.bator.nhsc.util.XmlUtil;

public class CommentsRunnable implements Runnable {
	String TAG = getClass().getSimpleName();
	
	String allCommentsLink;
	ICommentsFinishedListener listener;
	
	public CommentsRunnable(String allCommentsLink, ICommentsFinishedListener listener) {
		super();
		this.allCommentsLink = allCommentsLink;
		this.listener = listener;
	}
	public void run() {
		List<Comment> comments = new ArrayList<CommentsRunnable.Comment>();
		try {
			listener.commentsStarted();
			final Document dom = NetUtil.getXmlFromUrl(allCommentsLink);
			NodeList entriesNodes = dom.getElementsByTagName("item");
			if (entriesNodes != null) {
				for (int i = 0; i < entriesNodes.getLength(); i++) {
					String title = XmlUtil.getChildText(entriesNodes.item(i), "title");
					String body = XmlUtil.getTextFromNodeRecursive(XmlUtil.getChildElementByName(entriesNodes.item(i), "description"));
					Comment comment = new Comment(title, body);
					comments.add(comment);
				}
			}
		} catch (Exception e) {
			Log.e(TAG, "Error: ", e);
		} finally {
			listener.commentsFinished(comments);
		}
	}
	static interface ICommentsFinishedListener {
		void commentsFinished(List<Comment> comments);
		void commentsStarted();
	}
	static class Comment {
		
		public Comment(String title, String body) {
			super();
			this.title = title.replaceAll("&amp;apos;", "'");
			this.body = body.replaceAll("&amp;apos;", "'");
		}
		public String title;
		public String body;

		public String toString() {
			return "Comment [title=" + title + ", body=" + body + "]";
		}
	}
}
