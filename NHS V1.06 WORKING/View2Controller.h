
#import <UIKit/UIKit.h>


@interface View2Controller : UIViewController <UIAlertViewDelegate, UIActionSheetDelegate >{
	
	IBOutlet UIImageView * logo ;
	IBOutlet UIView * view2Bar ;
	IBOutlet UIImage * homeButtonPicture ;
	BOOL avanzar ;
}

-(IBAction)AandE999Button ;
-(IBAction)WalkinCenterButton ;
-(IBAction)GPButton ;
-(IBAction)HospitalButton ;
-(IBAction)PharmacistButton ;
-(IBAction)DentalButton ;
-(IBAction)SexHealthButton;


//-(IBAction)linkButtonPressed:(id)sender ;

@end
