

#import <UIKit/UIKit.h>


@interface browserNHSViewGPAppointment : UIViewController<UIWebViewDelegate> {

	IBOutlet UIWebView * TheWebView;
	IBOutlet UIActivityIndicatorView * activity ;
	
}

@property (nonatomic, retain) IBOutlet UIActivityIndicatorView *activity;
@property (nonatomic, retain) IBOutlet UIWebView *TheWebView;



-(IBAction)GoHome:(id)sender ;
-(IBAction)GoBack:(id)sender ;
-(IBAction)GoForward:(id)sender ;


@end