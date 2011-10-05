#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface AboutVC : VCBase <UIWebViewDelegate> {

	IBOutlet UIWebView* webView;
	
}

@property(nonatomic,retain) IBOutlet UIWebView* webView;

@end
