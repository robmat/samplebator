

#import <UIKit/UIKit.h>

NSString * informacion ;

@interface viewWalkinMapDetails : UIViewController {

	IBOutlet UILabel * labelNombre ;
	IBOutlet UILabel * labelPartner ;
	IBOutlet UILabel * labelManagerName ;
	IBOutlet UITextView * labelManagerMail ;
	IBOutlet UILabel * labelAddress1 ;
	IBOutlet UILabel * labelAddress2 ;
	IBOutlet UILabel * labelAddress2b ;
	IBOutlet UITextView * labelPhone ;
	IBOutlet UILabel * labelAddress1b ;
	
	BOOL avanzar ;
	
}



-(NSString *) dataFilePathWalkin ;

//-(IBAction)makeAppointmentButton ;
-(IBAction)sendMail:(id)sender ;
-(IBAction)goHome:(id)sender ;

@end