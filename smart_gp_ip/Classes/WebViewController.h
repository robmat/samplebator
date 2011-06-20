#import <UIKit/UIKit.h>
#import "CommonViewControllerBase.h"

@interface WebViewController : CommonViewControllerBase <UIWebViewDelegate> {
	IBOutlet UIWebView* webView;
	NSString* url;
	IBOutlet UIImageView* imageView;
}

@property (nonatomic, retain) UIWebView* webView;
@property (nonatomic, retain) NSString* url;

@end
