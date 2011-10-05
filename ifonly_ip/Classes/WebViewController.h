#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface WebViewController : VCBase <UIWebViewDelegate> {
	IBOutlet UIWebView* webView;
	NSString* url;
}

@property (nonatomic, retain) UIWebView* webView;
@property (nonatomic, retain) NSString* url;

@end
