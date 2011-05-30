#import <UIKit/UIKit.h>


@interface WebViewController : UIViewController {
	IBOutlet UIWebView* webView;
	NSString* url;
}

@property (nonatomic, retain) UIWebView* webView;
@property (nonatomic, retain) NSString* url;

@end
