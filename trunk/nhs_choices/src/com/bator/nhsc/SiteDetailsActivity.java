package com.bator.nhsc;

import java.util.List;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import android.app.Activity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bator.nhsc.CommentsRunnable.Comment;
import com.bator.nhsc.CommentsRunnable.ICommentsFinishedListener;
import com.bator.nhsc.ResultItemizedOverlay.Entry;
import com.bator.nhsc.net.NetUtil;
import com.bator.nhsc.util.XmlUtil;
import com.google.gson.Gson;

public class SiteDetailsActivity extends Activity implements ICommentsFinishedListener {
	public static final String EXTRA_ENTRY_GSON_KEY = "EXTRA_ENTRY_GSON_KEY";
	String TAG = getClass().getSimpleName();
	Runnable ratingRunnable;
	String allCommentsLink;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.site_details);
		final Entry entry = new Gson().fromJson(getIntent().getStringExtra(EXTRA_ENTRY_GSON_KEY), Entry.class);
		((TextView) findViewById(R.id.site_details_name)).setText(entry.name);
		((TextView) findViewById(R.id.site_details_email_text)).setText(entry.email);
		((TextView) findViewById(R.id.site_details_telephone_text)).setText(entry.telephone);
		((TextView) findViewById(R.id.site_details_postcode_text)).setText(entry.postcode);
		for (String addressComponent : entry.addressLines) {
			LinearLayout addressLayout = (LinearLayout) findViewById(R.id.site_details_address_lines_layout);
			TextView textView = getTextView();
			textView.setText(addressComponent);
			addressLayout.addView(textView, 0);
		}
		ratingRunnable = new Runnable() {
			public void run() {
				try {
					showRatingIndicator();
					final Document dom = NetUtil.getXmlFromUrl(entry.detailsLink);
					NodeList linksNodes = dom.getElementsByTagName("d2p1:Link");
					for (int i = 0; i < linksNodes.getLength(); i++) {
						if (XmlUtil.getChildText(linksNodes.item(i), "d2p1:Text").equalsIgnoreCase("all comments")) {
							allCommentsLink = XmlUtil.getChildText(linksNodes.item(i), "d2p1:Uri");
						}
					}
					Runnable r = new Runnable() {
						public void run() {
							LinearLayout ratingsLayout = (LinearLayout) findViewById(R.id.site_details_rating_layout_id);
							NodeList ratingsNodeList = dom.getElementsByTagName("rating");
							for (int i = 0; i < ratingsNodeList.getLength(); i++) {
								String questionText = XmlUtil.getChildText(ratingsNodeList.item(i), "questionText");
								String answerText = XmlUtil.getChildText(ratingsNodeList.item(i), "answerText");
								Node answerMetric = XmlUtil.getChildElementByName(ratingsNodeList.item(i), "answerMetric");
								int answerValue = (int) Math.round(Double.parseDouble(XmlUtil.getAttributeValue(answerMetric, "value")));
								int maxValue = (int) Math.round(Double.parseDouble(XmlUtil.getAttributeValue(answerMetric, "maxValue")));
								View ratingsView = getLayoutInflater().inflate(R.layout.rating_layout, ratingsLayout, false);
								((TextView) ratingsView.findViewById(R.id.rating_layout_question_text_id)).setText(questionText + ":");
								((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setText(answerText);
								if (maxValue == 5) {
									switch (answerValue) {
									case 1:
										((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setCompoundDrawablesWithIntrinsicBounds(R.drawable.rating_1, 0, 0, 0);
										break;
									case 2:
										((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setCompoundDrawablesWithIntrinsicBounds(R.drawable.rating_2, 0, 0, 0);
										break;
									case 3:
										((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setCompoundDrawablesWithIntrinsicBounds(R.drawable.rating_3, 0, 0, 0);
										break;
									case 4:
										((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setCompoundDrawablesWithIntrinsicBounds(R.drawable.rating_4, 0, 0, 0);
										break;
									case 5:
										((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setCompoundDrawablesWithIntrinsicBounds(R.drawable.rating_5, 0, 0, 0);
										break;
									}
									((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setCompoundDrawablePadding(5);
								}
								ratingsLayout.addView(ratingsView);
							}
						}
					};
					runOnUiThread(r);
				} catch (Exception e) {
					Log.e(TAG, "Error: ", e);
				} finally {
					hideRatingIndicator();
					if (allCommentsLink != null) {
						new Thread(new CommentsRunnable(allCommentsLink, SiteDetailsActivity.this)).start();
					} else {
						hideCommentsIndicator();
					}
				}
			}
		};
		new Thread(ratingRunnable).start();
	}

	public TextView getTextView() {
		TextView textView = new TextView(this);
		textView.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.FILL_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));
		textView.setTextAppearance(this, android.R.style.TextAppearance_Medium);
		return textView;
	}

	private void showRatingIndicator() {
		runOnUiThread(new Runnable() {
			public void run() {
				findViewById(R.id.site_details_rating_indicator_layout_id).setVisibility(View.VISIBLE);
				findViewById(R.id.site_details_rating_indicator_id).setVisibility(View.VISIBLE);
			}
		});
	}

	private void hideRatingIndicator() {
		runOnUiThread(new Runnable() {
			public void run() {
				findViewById(R.id.site_details_rating_indicator_layout_id).setVisibility(View.GONE);
				findViewById(R.id.site_details_rating_indicator_id).setVisibility(View.GONE);
			}
		});
	}
	private void showCommentsIndicator() {
		runOnUiThread(new Runnable() {
			public void run() {
				findViewById(R.id.site_details_comments_indicator_layout_id).setVisibility(View.VISIBLE);
				findViewById(R.id.site_details_comments_indicator_id).setVisibility(View.VISIBLE);
			}
		});
	}

	private void hideCommentsIndicator() {
		runOnUiThread(new Runnable() {
			public void run() {
				findViewById(R.id.site_details_comments_indicator_layout_id).setVisibility(View.GONE);
				findViewById(R.id.site_details_comments_indicator_id).setVisibility(View.GONE);
			}
		});
	}
	public void commentsStarted() {
		showCommentsIndicator();
	}
	public void commentsFinished(final List<Comment> comments) {
		hideCommentsIndicator();
		runOnUiThread(new Runnable() {
			public void run() {
				LinearLayout ratingsLayout = (LinearLayout) findViewById(R.id.site_details_comments_layout_id);
				for (Comment comment : comments) {
					View ratingsView = getLayoutInflater().inflate(R.layout.rating_layout, ratingsLayout, false);
					((TextView) ratingsView.findViewById(R.id.rating_layout_question_text_id)).setText(comment.title);
					((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setText(comment.body);
					((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setTextAppearance(SiteDetailsActivity.this, android.R.style.TextAppearance_Medium);
					//((TextView) ratingsView.findViewById(R.id.rating_layout_value_text_id)).setTextColor(android.R.color.white);
					ratingsLayout.addView(ratingsView);
				}
			}
		});
	}
}
